<?php

declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use InvalidArgumentException;

final readonly class CartItemPrice
{
    private int $cents;

    public function __construct(int $cents)
    {
        if ($cents < 0) {
            throw new InvalidArgumentException('Price must be zero or positive.');
        }

        $this->cents = $cents;
    }

    public static function fromInt(int $cents): self
    {
        return new CartItemPrice($cents);
    }

    public static function fromFloat(float $euros): self
    {
        if ($euros < 0) {
            throw new InvalidArgumentException('Price must be zero or positive.');
        }

        return new CartItemPrice((int) round($euros * 100));
    }

    public function value(): int
    {
        return $this->cents;
    }

    public function asDecimal(): float
    {
        return $this->cents / 100;
    }

    public function increase(int $byCents): self
    {
        return new self($this->cents + $byCents);
    }

    public function multiply(int $factor): self
    {
        if ($factor < 0) {
            throw new InvalidArgumentException('Factor must be non-negative.');
        }

        return new self($this->cents * $factor);
    }

    public function equals(self $other): bool
    {
        return $this->cents === $other->value();
    }
}
