<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\AddItemToCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class AddItemToCartController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $bus,
    ) {
        $this->messageBus = $bus;
    }

    #[Route('/carts/{publicId}/items', name: 'cart_add_item', methods: ['POST'])]
    public function create(Request $request, string $publicId): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            if (!isset($data['price'], $data['quantity'], $data['product_id'])) {
                throw new LogicException('Missing required fields (price, quantity and product_id).');
            }

            $command = new AddItemToCartCommand(
                cartPublicId: $publicId,
                price: floatval($data['price']),
                quantity: intval($data['quantity']),
                productId: intval($data['product_id']),
                name: isset($data['name']) ? trim(strval($data['name'])) : null,
                color: isset($data['color']) ? trim(strval($data['color'])) : null,
                size: isset($data['size']) ? trim(strval($data['size'])) : null,
            );

            $cartItemPublicId = $this->handle($command);

            return new JsonResponse(
                ['id' => $cartItemPublicId, 'cart_id' => $publicId],
                Response::HTTP_CREATED
            );

        } catch (LogicException $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
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
