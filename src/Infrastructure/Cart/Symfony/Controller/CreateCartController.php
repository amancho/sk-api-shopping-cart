<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CreateCartCommand;
use Exception;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

readonly class CreateCartController
{
    public function __construct(private MessageBusInterface $bus) {}

    #[Route('/carts', name: 'cart_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                throw new LogicException('Invalid data');
            }

            $command = new CreateCartCommand(
                $data['userId'],
                $data['sessionId']
            );

            $this->validate($command);
            $this->bus->dispatch($command);

            return new JsonResponse([], Response::HTTP_CREATED);

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
