<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\UpdateCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateCartController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $bus)
    {
        $this->messageBus = $bus;
    }

    #[Route('/carts/{publicId}', name: 'cart_update', methods: ['PUT'])]
    public function update(Request $request, string $publicId): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            if (!isset($data['name'], $data['address'], $data['city'], $data['email'])) {
                throw new LogicException('Missing required fields (name, address, city and email).');
            }

            $command = UpdateCartCommand::fromPrimitives(
                publicId: $publicId,
                name: trim(strval($data['name'])),
                address: trim(strval($data['address'])),
                city: trim(strval($data['city'])),
                email: trim(strval($data['email'])),
                phone: isset($data['phone']) ? trim(strval($data['phone'])) : null,
                country: isset($data['country']) ? trim(strval($data['country'])) : null,
                province: isset($data['province']) ? trim(strval($data['province'])) : null,
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
