<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use App\Domain\Shared\Exception\InvalidUuid;
use App\Domain\Shared\ValueObject\Uuid;

readonly class CartPublicId
{
    private function __construct(private Uuid $uuid)
    {
    }

    /** @throws InvalidUuid */
    public static function create(): CartPublicId
    {
        return new CartPublicId(Uuid::create());
    }

    /** @throws InvalidUuid */
    public static function fromUuid(string $uuid): CartPublicId
    {
        return new CartPublicId(Uuid::fromString($uuid));
    }

    public function value(): string
    {
        return (string) $this->uuid;
    }
}
