<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

readonly class CartId
{
    private function __construct(private int $id)
    {
    }

    public static function fromInt(int $id): CartId
    {
        return new CartId($id);
    }

    public function value(): int
    {
        return $this->id;
    }

    public function equals(string $other): bool
    {
        return $other === (string) $this->id;
    }
}
