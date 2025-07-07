<?php declare(strict_types=1);

namespace  App\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Query\GetCartByPublicId;
use App\Application\Cart\Query\GetCartByPublicIdHandler;
use App\Domain\Cart\Exception\CartNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCartController extends AbstractController
{
    public function __construct(private readonly GetCartByPublicIdHandler $handler)
    {
    }

    #[Route('/carts/{publicId}', name: 'cart_show', methods: ['GET'])]
    public function show(string $publicId): JsonResponse
    {
        try {
            $club = $this->handler->__invoke(new GetCartByPublicId($publicId));

            return new JsonResponse($club->toArray());

        } catch (CartNotFoundException $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception) {
            return new JsonResponse(
                ['error' => 'Unknown error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
