<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use App\Domain\Cart\ValueObject\CartId;
use Exception;

class CartNotFoundException extends Exception
{
    public static function create(string $publicId): CartNotFoundException
    {
        return new CartNotFoundException(sprintf('Cart with public id [%s] not found', $publicId));
    }

    public static function createFromId(CartId $cartId): CartNotFoundException
    {
        return new CartNotFoundException(sprintf('Cart with id [%u] not found', $cartId->value()));
    }
}
