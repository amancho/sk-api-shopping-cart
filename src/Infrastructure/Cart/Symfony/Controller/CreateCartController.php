<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CreateCartCommand;
use App\Domain\Cart\Exception\CartValidationException;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateCartController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $bus,
        private readonly ValidatorInterface $validator
    ) {
        $this->messageBus = $bus;
    }

    #[Route('/carts', name: 'cart_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            $command = new CreateCartCommand(
                userId: isset($data['userId']) ? intval($data['userId']) : null,
                sessionId: isset($data['sessionId']) ? strval($data['sessionId']) : null,
            );

            $this->validate($command);
            $cartPublicId = $this->handle($command);

            return new JsonResponse(['id' => $cartPublicId], Response::HTTP_CREATED);

        } catch (LogicException $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (CartValidationException $exception) {
            return new JsonResponse(
                ['error' => $exception->getErrors()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception) {
            return new JsonResponse(
                ['error' => 'Unknown error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @throws CartValidationException
     */
    private function validate(CreateCartCommand $command): void
    {
        $errors = $this->validator->validate($command);
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        if (count($errorMessages) > 0) {
            throw CartValidationException::create($errorMessages);
        }
    }
}
