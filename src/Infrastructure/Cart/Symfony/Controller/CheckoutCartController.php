<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CheckoutCartCommand;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartValidationException;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CheckoutCartController
{
    public function __construct(
        private MessageBusInterface $bus,
        private ValidatorInterface  $validator
    ) {}

    #[Route('/carts/{publicId}/checkout', name: 'cart_checkout', methods: ['PUT'])]
    public function checkout(Request $request, string $publicId): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            $command = new CheckoutCartCommand(
                publicId: $publicId,
                checkoutId: $data['checkoutId'] ?? strval(null),
            );

            $this->validate($command);
            $this->bus->dispatch($command);

            return new JsonResponse([], Response::HTTP_NO_CONTENT);

        } catch (ExceptionInterface | LogicException $exception) {
            return new JsonResponse(
                ['error' => $exception->getPrevious()?->getMessage() ?? $exception->getMessage()],
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
    private function validate(CheckoutCartCommand $command): void
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
