<?php declare(strict_types=1);

namespace App\Domain\Order\ValueObject;

readonly class OrderId
{
    private function __construct(private int $id)
    {
    }

    public static function fromInt(int $id): OrderId
    {
        return new OrderId($id);
    }

    public function value(): int
    {
        return $this->id;
    }

    public function equals(OrderId $other): bool
    {
        return $other->value() === $this->id;
    }
}
