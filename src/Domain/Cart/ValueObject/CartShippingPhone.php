<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use InvalidArgumentException;

final class CartShippingPhone
{
    private string $phone;

    public function __construct(string $phone)
    {
        if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            throw new InvalidArgumentException(sprintf('Invalid phone number: "%s"', $phone));
        }

        $this->phone = $phone;
    }

    public static function fromString(string $value): CartShippingPhone
    {
        return new CartShippingPhone($value);
    }

    public function value(): string
    {
        return $this->phone;
    }

    public function countryCode(): ?string
    {
        // Si el número tiene un prefijo internacional (p. ej., "+34"), extraerlo
        if (str_starts_with($this->phone, '+')) {
            return \explode(' ', $this->phone, 2)[0];
        }

        return null;
    }
}
