<?php declare(strict_types=1);

namespace App\Domain\Cart\Events;

use App\Domain\Cart\ValueObject\CartId;

final readonly class CartCheckoutEvent
{
    public function __construct(
        public CartId $cartId,
        public string $checkoutId
    ) {}
}
