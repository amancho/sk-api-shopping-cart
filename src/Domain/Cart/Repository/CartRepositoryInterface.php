<?php declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\ValueObject\CartId;

interface CartRepositoryInterface
{
    public function findById(CartId $cartId): ?Cart;

    public function findByPublicId(string $publicId): ?Cart;

    public function save(Cart $cart): ?Cart;

    public function update(Cart $cart): ?Cart;
}
