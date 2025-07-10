<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use App\Domain\Cart\ValueObject\CartId;
use Exception;

class CartEmptyException extends Exception
{
    public static function create(CartId $id): CartEmptyException
    {
        return new CartEmptyException(sprintf('Cart with id [%s] is empty and cannot be checked out.', $id->value()));
    }
}
