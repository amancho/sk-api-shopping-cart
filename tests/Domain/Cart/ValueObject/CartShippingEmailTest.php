<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\ValueObject;

use App\Domain\Cart\ValueObject\CartShippingEmail;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CartShippingEmailTest extends TestCase
{
    public function testItAcceptsValidEmail(): void
    {
        $email = new CartShippingEmail('john@example.com');

        $this->assertSame('john@example.com', $email->value());
        $this->assertSame('example.com', $email->domain());
    }

    public function testItAcceptsValidEmailUsingFromString(): void
    {
        $email = CartShippingEmail::fromString('jane@domain.com');

        $this->assertSame('jane@domain.com', $email->value());
        $this->assertSame('domain.com', $email->domain());
    }

    public function testItThrowsExceptionForInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address: "not-an-email"');

        new CartShippingEmail('not-an-email');
    }

    public function testDomainExtractionWorks(): void
    {
        $email = new CartShippingEmail('some.user@sub.domain.co.uk');

        $this->assertSame('sub.domain.co.uk', $email->domain());
    }
}
