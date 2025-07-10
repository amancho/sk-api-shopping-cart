<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\Entity;

use App\Domain\Cart\ValueObject\CartShippingAddress;
use PHPUnit\Framework\TestCase;

final class CartShippingAddressTest extends TestCase
{
    public function testConstructorStoresValuesCorrectly(): void
    {
        $address = new CartShippingAddress(
            name: 'John Doe',
            address: '123 Main St',
            city: 'Springfield',
            postalCode: '12345',
            province: 'IL',
            country: 'USA'
        );

        $this->assertSame('John Doe', $address->name());
        $this->assertSame('123 Main St', $address->address());
        $this->assertSame('Springfield', $address->city());
        $this->assertSame('12345', $address->postalCode());
        $this->assertSame('IL', $address->province());
        $this->assertSame('USA', $address->country());
    }

    public function testFromValuesCreatesInstanceCorrectly(): void
    {
        $address = CartShippingAddress::build(
            name: 'Jane Smith',
            address: '456 Elm St',
            city: 'Shelbyville',
            postalCode: '67890',
            province: 'IN',
            country: 'USA'
        );

        $this->assertSame('Jane Smith', $address->name());
        $this->assertSame('456 Elm St', $address->address());
        $this->assertSame('Shelbyville', $address->city());
        $this->assertSame('67890', $address->postalCode());
        $this->assertSame('IN', $address->province());
        $this->assertSame('USA', $address->country());
    }

    public function testToArrayReturnsCorrectData(): void
    {
        $address = new CartShippingAddress(
            name: 'Alice',
            address: '789 Oak Ave',
            city: 'Capital City',
            postalCode: '24680',
            province: 'CA',
            country: 'USA'
        );

        $expected = [
            'name'        => 'Alice',
            'address'     => '789 Oak Ave',
            'city'        => 'Capital City',
            'postal_code' => '24680',
            'province'    => 'CA',
            'country'     => 'USA',
        ];

        $this->assertSame($expected, $address->toArray());
    }

    public function testNullableFieldsDefaultToNull(): void
    {
        $address = new CartShippingAddress();

        $this->assertNull($address->name());
        $this->assertNull($address->address());
        $this->assertNull($address->city());
        $this->assertNull($address->postalCode());
        $this->assertNull($address->province());
        $this->assertNull($address->country());

        $this->assertSame([
            'name'        => null,
            'address'     => null,
            'city'        => null,
            'postal_code' => null,
            'province'    => null,
            'country'     => null,
        ], $address->toArray());
    }
}
