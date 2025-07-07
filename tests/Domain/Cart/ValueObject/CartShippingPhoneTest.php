<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\ValueObject;

use App\Domain\Cart\ValueObject\CartShippingPhone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CartShippingPhoneTest extends TestCase
{
    public function testItAcceptsValidPhoneNumber(): void
    {
        $phone = new CartShippingPhone('+34600111222');

        $this->assertSame('+34600111222', $phone->value());
        $this->assertSame('+34600111222', $phone->countryCode());
    }

    public function testItAcceptsValidPhoneWithoutPlus(): void
    {
        $phone = new CartShippingPhone('600111222');

        $this->assertSame('600111222', $phone->value());
        $this->assertNull($phone->countryCode());
    }

    public function testItAcceptsPhoneFromString(): void
    {
        $phone = CartShippingPhone::fromString('+12125551234');

        $this->assertSame('+12125551234', $phone->value());
        $this->assertSame('+12125551234', $phone->countryCode());
    }

    public function testItThrowsExceptionOnShortNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number: "12345"');

        new CartShippingPhone('12345');
    }

    public function testItThrowsExceptionOnLetters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number: "abc123def"');

        new CartShippingPhone('abc123def');
    }

    public function testItThrowsExceptionOnInvalidCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number: "+34 600-111-222"');

        new CartShippingPhone('+34 600-111-222');
    }
}
