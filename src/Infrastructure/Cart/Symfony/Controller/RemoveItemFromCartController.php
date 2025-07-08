<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\RemoveItemFromCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final readonly class RemoveItemFromCartController
{

    public function __construct(
        private MessageBusInterface $bus
    ) {
    }

    #[Route('/carts/{publicId}/items/{itemPublicId}', name: 'cart_delete_item', methods: ['DELETE'])]
    public function delete(string $publicId, string $itemPublicId): JsonResponse
    {
        try {
            $command = new RemoveItemFromCartCommand(
                cartPublicId: $publicId,
                cartItemPublicId: $itemPublicId,
            );

            $this->bus->dispatch($command);

            return new JsonResponse([], Response::HTTP_NO_CONTENT);

        } catch (ExceptionInterface | LogicException $exception) {
            return new JsonResponse(
                ['error' => $exception->getPrevious()?->getMessage() ?? $exception->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception) {
            return new JsonResponse(
                ['error' => 'Unknown error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
