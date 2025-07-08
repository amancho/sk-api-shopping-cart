<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\Entity;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Domain\Shared\ValueObject\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testItFailsInvalidUuid(): void
    {
        $this->expectException(InvalidUuid::class);

        CartItem::build(
            id: 0,
            publicId: '',
            cartId: 0,
            price: 0,
            quantity: 0,
            productId: 0
        );
    }

    public function testItFailsInvalidPrice(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CartItem::build(
            id: 0,
            publicId: Uuid::asString(),
            cartId: 0,
            price: -1,
            quantity: 0,
            productId: 0
        );
    }

    public function testItFailsInvalidQuantity(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CartItem::build(
            id: 0,
            publicId: Uuid::asString(),
            cartId: 0,
            price: 5,
            quantity: 0,
            productId: 0
        );
    }
}
