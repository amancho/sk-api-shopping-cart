<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use App\Domain\Cart\ValueObject\CartStatus;
use Exception;

class CartInvalidStatusException extends Exception
{
    public static function create(CartStatus $status): CartInvalidStatusException
    {
        return new CartInvalidStatusException(sprintf('Cart operation with status [%s] is not allowed', $status->value));
    }
}
