<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

readonly class CartItemId
{
    private function __construct(private int $id)
    {
    }

    public static function fromInt(int $id): CartItemId
    {
        return new CartItemId($id);
    }

    public function value(): int
    {
        return $this->id;
    }

    public function equals(CartItemId $other): bool
    {
        return $this->value() === $other->value();
    }
}
