<?php declare(strict_types=1);

namespace App\Tests\Domain\Order\Entity;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\ValueObject\OrderStatus;
use PHPUnit\Framework\TestCase;
use ValueError;

class OrderTest extends TestCase
{
    public function testItOrderCreate(): void
    {
        $metadata = [
            'cart_id'   => 123,
            'cart_code' => 'TEST',
            'notes'     => null
        ];

        $order = Order::create(
            total_amount: 123456,
            metadata: $metadata,
            shippingAddress: [],
        );

        $this->assertEquals(123456, $order->totalAmount()->value());
        $this->assertEquals(OrderStatus::NEW, $order->status());
        $this->assertEquals($metadata, $order->metadata()->toArray());
        $this->assertEquals(0, $order->id()->value());
        $this->assertNotEmpty($order->code()->value());
        $this->assertEmpty($order->items());
        $this->assertNull($order->userId());
        $this->assertNull($order->shippingAddress());
        $this->assertTrue($order->isActive());
    }

    public function testItFailsIncorrectStatus(): void
    {
        $this->expectException(ValueError::class);

        Order::build(
            id: 123,
            total_amount: 0,
            code: 'TEST',
            status: 'TEST'
        );
    }
}
