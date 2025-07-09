<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\UpdateItemFromCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateItemFromCartController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $bus,
    ) {
        $this->messageBus = $bus;
    }

    #[Route('/carts/{publicId}/items/{itemPublicId}', name: 'cart_update_item', methods: ['PATCH'])]
    public function update(Request $request, string $publicId, string $itemPublicId): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            $command = UpdateItemFromCartCommand::fromPrimitives(
                cartItemPublicId: $itemPublicId,
                cartPublicId: $publicId,
                price: isset($data['price']) ? floatval($data['price']) : null,
                quantity: isset($data['quantity']) ? intval($data['quantity']) : null,
                productId: isset($data['product_id']) ? intval($data['product_id']) : null,
                name: isset($data['name']) ? trim(strval($data['name'])) : null,
                color: isset($data['color']) ? trim(strval($data['color'])) : null,
                size: isset($data['size']) ? trim(strval($data['size'])) : null,
            );

            $this->handle($command);

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
