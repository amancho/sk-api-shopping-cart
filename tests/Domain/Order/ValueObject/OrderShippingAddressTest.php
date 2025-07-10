<?php declare(strict_types=1);

namespace App\Tests\Domain\Order\ValueObject;

use App\Domain\Order\ValueObject\OrderShippingAddress;
use PHPUnit\Framework\TestCase;

class OrderShippingAddressTest extends TestCase
{
    public function testItCanBeCreatedFromArray(): void
    {
        $input = [
            'name' => 'John Doe',
            'address' => '123 Main St',
            'city' => 'Springfield',
            'postal_code' => '12345',
            'province' => 'Illinois',
            'country' => 'USA',
        ];

        $address = OrderShippingAddress::fromArray($input);

        $this->assertEquals('John Doe', $address->name());
        $this->assertEquals('123 Main St', $address->address());
        $this->assertEquals('Springfield', $address->city());
        $this->assertEquals('12345', $address->postalCode());
        $this->assertEquals('Illinois', $address->province());
        $this->assertEquals('USA', $address->country());

        $this->assertEquals($input, $address->toArray());
    }

    public function testItHandlesMissingFieldsInFromArray(): void
    {
        $input = [
            'name' => 'Alice',
            // 'address' => missing
            'city' => 'Barcelona',
        ];

        $address = OrderShippingAddress::fromArray($input);

        $this->assertEquals('Alice', $address->name());
        $this->assertNull($address->address());
        $this->assertEquals('Barcelona', $address->city());
        $this->assertNull($address->postalCode());
        $this->assertNull($address->province());
        $this->assertNull($address->country());

        $expected = [
            'name' => 'Alice',
            'address' => null,
            'city' => 'Barcelona',
            'postal_code' => null,
            'province' => null,
            'country' => null,
        ];

        $this->assertEquals($expected, $address->toArray());
    }

    public function testItCanBeBuiltWithNamedParams(): void
    {
        $address = OrderShippingAddress::build(
            name: 'Bob',
            address: 'Calle Falsa 123',
            city: 'Madrid',
            postalCode: '28080',
            province: 'Madrid',
            country: 'España'
        );

        $this->assertEquals('Bob', $address->name());
        $this->assertEquals('Calle Falsa 123', $address->address());
        $this->assertEquals('Madrid', $address->city());
        $this->assertEquals('28080', $address->postalCode());
        $this->assertEquals('Madrid', $address->province());
        $this->assertEquals('España', $address->country());
    }

    public function testToArrayAlwaysReturnsAllKeys(): void
    {
        $address = new OrderShippingAddress();

        $this->assertEquals([
            'name' => null,
            'address' => null,
            'city' => null,
            'postal_code' => null,
            'province' => null,
            'country' => null,
        ], $address->toArray());
    }
}
