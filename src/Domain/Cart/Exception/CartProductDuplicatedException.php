<?php declare(strict_types=1);

namespace App\Domain\Cart\Exception;

use Exception;

class CartProductDuplicatedException extends Exception
{
    public static function create(int $productId): CartProductDuplicatedException
    {
        return new CartProductDuplicatedException(sprintf('The product with id [%u] is already in the cart.', $productId));
    }
}
