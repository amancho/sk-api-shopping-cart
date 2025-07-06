<?php declare(strict_types=1);

namespace App\Application\Cart\Query;

use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;

final readonly class GetCartByPublicIdHandler
{
    public function __construct(
        private CartRepositoryInterface $repository
    ) {}

    /**
     * @throws CartNotFoundException
     */
    public function __invoke(GetCartByPublicId $query): GetCartByPublicIdResponse
    {
        $cart = $this->repository->findByPublicId($query->publicId());
        if ($cart === null) {
            throw CartNotFoundException::create($query->publicId());
        }

        return new GetCartByPublicIdResponse($cart);
    }
}
