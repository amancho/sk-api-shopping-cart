<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use Exception;

class CartNotFoundException extends Exception
{
    public static function create(string $publicId): CartNotFoundException
    {
        return new CartNotFoundException(sprintf('Cart with id %s not found', $publicId));
    }
}
