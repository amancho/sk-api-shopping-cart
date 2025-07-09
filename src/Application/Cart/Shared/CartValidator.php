<?php declare(strict_types=1);

namespace App\Application\Cart\Shared;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\ValueObject\CartPublicId;

trait CartValidator
{
    /**
     * @throws CartNotFoundException
     * @throws CartInvalidStatusException
     */
    private function getActiveCart(CartPublicId $cartPublicId): Cart
    {
        $cart = $this->cartRepository->findByPublicId($cartPublicId->value());

        if ($cart === null) {
            throw CartNotFoundException::create($cartPublicId->value());
        }

        if (!$cart->isActive()) {
            throw CartInvalidStatusException::create($cart->status());
        }

        return $cart;
    }
}
