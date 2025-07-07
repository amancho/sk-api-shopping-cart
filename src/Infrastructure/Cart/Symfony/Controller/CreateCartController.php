<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CreateCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CreateCartController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $bus)
    {
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
                userId: $data['userId'] !== null ? intval($data['userId']) : null,
                sessionId: $data['sessionId'] !== null ? strval($data['sessionId']) : null,
            );

            $this->validate($command);
            $cartPublicId = $this->handle($command);

            return new JsonResponse(['id' => $cartPublicId], Response::HTTP_CREATED);

        } catch (LogicException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return new JsonResponse(['error' => 'Unknown error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws LogicException
     */
    private function validate(CreateCartCommand $command): void
    {
        if ($command->sessionId() === null && $command->userId() === null) {
            throw new LogicException('Invalid data');
        }
    }
}
