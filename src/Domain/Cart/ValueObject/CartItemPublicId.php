<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use App\Domain\Shared\Exception\InvalidUuid;
use App\Domain\Shared\ValueObject\Uuid;

readonly class CartItemPublicId
{
    private function __construct(private Uuid $uuid)
    {
    }

    /** @throws InvalidUuid */
    public static function create(): CartItemPublicId
    {
        return new CartItemPublicId(Uuid::create());
    }

    /** @throws InvalidUuid */
    public static function fromUuid(string $uuid): CartItemPublicId
    {
        return new CartItemPublicId(Uuid::fromString($uuid));
    }

    public function value(): string
    {
        return (string) $this->uuid;
    }

    public function equals(CartItemPublicId $other): bool
    {
        return $this->value() === $other->value();
    }
}
