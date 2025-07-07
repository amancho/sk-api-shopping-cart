<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use InvalidArgumentException;

final class CartShippingEmail
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(\sprintf('Invalid email address: "%s"', $email));
        }

        $this->email = $email;
    }

    public static function fromString(string $value): CartShippingEmail
    {
        return new CartShippingEmail($value);
    }

    public function value(): string
    {
        return $this->email;
    }

    public function domain(): string
    {
        return \substr(\strrchr($this->email, '@'), 1);
    }
}
