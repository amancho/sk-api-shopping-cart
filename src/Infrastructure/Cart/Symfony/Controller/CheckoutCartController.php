<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CheckoutCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

readonly class CheckoutCartController
{
    public function __construct(private MessageBusInterface $bus) {}

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
                checkoutId: isset($data['checkoutId']) ? strval($data['checkoutId']) : null,
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
