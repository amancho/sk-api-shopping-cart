<?php declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartItemId;

interface CartItemRepositoryInterface
{
    public function findById(CartItemId $cartItemId): ?CartItem;

    public function findByPublicId(string $publicId): ?CartItem;

    public function findByCartIdAndProductId(CartId $cartId, int $productId): ?CartItem;

    public function save(CartItem $cartItem): ?CartItem;

    public function update(CartItem $cartItem): ?CartItem;

    public function delete(CartItem $cartItem): void;
}
