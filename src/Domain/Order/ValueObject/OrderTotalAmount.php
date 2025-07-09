<?php declare(strict_types=1);

namespace App\Domain\Order\ValueObject;

use InvalidArgumentException;

final readonly class OrderTotalAmount
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('Total amount must be at least 1.');
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

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }
}
