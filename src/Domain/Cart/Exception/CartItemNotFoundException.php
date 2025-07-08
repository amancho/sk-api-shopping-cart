<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use App\Domain\Cart\ValueObject\CartItemId;
use Exception;

class CartItemNotFoundException extends Exception
{
    public static function create(string $publicId): CartItemNotFoundException
    {
        return new CartItemNotFoundException(sprintf('Cart item with public id [%s] not found', $publicId));
    }

    public static function createFromId(CartItemId $cartId): CartItemNotFoundException
    {
        return new CartItemNotFoundException(sprintf('Cart item with id [%u] not found', $cartId->value()));
    }
}
