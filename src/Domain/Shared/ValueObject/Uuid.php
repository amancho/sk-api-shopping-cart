<?php declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidUuid;
use Ramsey\Uuid\Uuid as UuidRamsey;
use Stringable;

class Uuid implements Stringable
{
    protected string $value;

    /**
     * @throws InvalidUuid
     */
    protected function __construct(string $value)
    {
        $this->checkIfUuidIsValid($value);

        $this->value = $value;
    }

    /**
     * @throws InvalidUuid
     */
    public static function create(): Uuid
    {
        return new self(UuidRamsey::uuid4()->toString());
    }

    public static function asString(): string
    {
        return UuidRamsey::uuid4()->toString();
    }

    /**
     * @throws InvalidUuid
     */
    public static function fromString(string $uuid): Uuid
    {
        return new self($uuid);
    }

    /** @throws InvalidUuid */
    public static function createDeterministic(string $seed): Uuid
    {
        return new self(UuidRamsey::uuid5(UuidRamsey::NAMESPACE_DNS, $seed)->toString());
    }

    public function equals(Uuid $other): bool
    {
        return (string) $other === (string) $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws InvalidUuid
     */
    protected function checkIfUuidIsValid(string $uuid): void
    {
        if (UuidRamsey::isValid($uuid)) {
            return;
        }

        throw InvalidUuid::invalidValue($uuid);
    }
}
