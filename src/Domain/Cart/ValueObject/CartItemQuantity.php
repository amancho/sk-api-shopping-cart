<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use InvalidArgumentException;

final class CartItemQuantity
{
    private readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1.');
        }

        $this->value = $value;
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function increase(int $by = 1): self
    {
        return new self($this->value + $by);
    }

    public function decrease(int $by = 1): self
    {
        $newValue = $this->value - $by;

        if ($newValue < 1) {
            throw new InvalidArgumentException('Quantity cannot be less than 1.');
        }

        return new self($newValue);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }
}
