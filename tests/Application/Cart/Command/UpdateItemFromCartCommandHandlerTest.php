<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\UpdateItemFromCartCommand;
use App\Application\Cart\Command\UpdateItemFromCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartProductDuplicatedException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
class UpdateItemFromCartCommandHandlerTest extends TestCase
{
    private UpdateItemFromCartCommandHandler $handler;
    private CartRepositoryInterface $cartRepository;
    private CartItemRepositoryInterface $cartItemRepository;

    protected function setUp(): void
    {
        $this->cartRepository       = $this->createMock(CartRepositoryInterface::class);
        $this->cartItemRepository   = $this->createMock(CartItemRepositoryInterface::class);

        $this->handler = new UpdateItemFromCartCommandHandler(
            $this->cartRepository,
            $this->cartItemRepository
        );
    }

    public function testShouldThrowExceptionIfCartNotFound(): void
    {
        $cardPublicId = Uuid::create()->value();

        $command = new UpdateItemFromCartCommand(
            cartItemPublicId: Uuid::create()->value(),
            cartPublicId: $cardPublicId,
            price: 10.5,
            quantity: 1,
            productId: 55
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cardPublicId)
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfCartNotAvailable(): void
    {
        $cart = Cart::build(
            id: 0,
            publicId: Uuid::create()->value(),
            code: 'TEST-002',
            status: CartStatus::COMPLETED,
        );

        $command = new UpdateItemFromCartCommand(
            cartItemPublicId: Uuid::create()->value(),
            cartPublicId: $cart->publicId()->value(),
            price: 10.5,
            quantity: 1,
            productId: 55
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->expectException(CartInvalidStatusException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfProductNotFound(): void
    {
        $cart = Cart::create();

        $command = new UpdateItemFromCartCommand(
            cartItemPublicId: Uuid::create()->value(),
            cartPublicId: $cart->publicId()->value(),
            price: 10.5,
            quantity: 1,
            productId: 55
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository->expects($this->once())
            ->method('findByPublicId')
            ->willReturn(null);

        $this->expectException(CartItemNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testUpdateCartItemSuccessfully(): void
    {
        $cart       = Cart::create();
        $productId  = 55;

        $cartItem   = CartItem::create(
            cartId: $cart->id(),
            price: CartItemPrice::fromFloat(10.5),
            quantity:  CartItemQuantity::fromInt(1),
            productId: $productId
        );

        $command = new UpdateItemFromCartCommand(
            cartItemPublicId: $cartItem->publicId()->value(),
            cartPublicId: $cart->publicId()->value(),
            price: 10.5,
            quantity: 1,
            productId: $productId
        );

        $this->cartRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->with($cartItem->publicId()->value())
            ->willReturn($cartItem);

        $this->cartItemRepository
            ->expects($this->once())
            ->method('update')
            ->willReturn($cartItem);

        $this->handler->__invoke($command);
    }
}
