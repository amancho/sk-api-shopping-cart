<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\RemoveItemFromCartCommand;
use App\Application\Cart\Command\RemoveItemFromCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartValidationException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class RemoveItemFromCartCommandHandlerTest extends TestCase
{
    private RemoveItemFromCartCommandHandler $handler;
    private CartRepositoryInterface $cartRepository;
    private CartItemRepositoryInterface $cartItemRepository;

    protected function setUp(): void
    {
        $this->cartRepository       = $this->createMock(CartRepositoryInterface::class);
        $this->cartItemRepository   = $this->createMock(CartItemRepositoryInterface::class);

        $this->handler = new RemoveItemFromCartCommandHandler(
            $this->cartRepository,
            $this->cartItemRepository
        );
    }

    public function testShouldThrowExceptionIfCartNotFound(): void
    {
        $cardPublicId = Uuid::create()->value();

        $command = new RemoveItemFromCartCommand($cardPublicId, Uuid::create()->value());

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cardPublicId)
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfCartNotAvailable(): void
    {
        $cardPublicId = Uuid::create()->value();
        $cart = Cart::build(
            id: 0,
            publicId: $cardPublicId,
            code: 'TEST-002',
            status: CartStatus::COMPLETED,
        );

        $command = new RemoveItemFromCartCommand($cardPublicId, Uuid::create()->value());

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cardPublicId)
            ->willReturn($cart);

        $this->expectException(CartInvalidStatusException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfCartItemNotFound(): void
    {
        $cart = Cart::create();

        $command = new RemoveItemFromCartCommand($cart->publicId()->value(), Uuid::create()->value());

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

    public function testShouldThrowExceptionIfCartNotSame(): void
    {
        $cart = Cart::create();

        $cartItem = CartItem::create(
            cartId: CartId::fromInt(66),
            price: CartItemPrice::fromInt(1057),
            quantity: CartItemQuantity::fromInt(1),
            productId: 66
        );

        $command = new RemoveItemFromCartCommand(
            $cart->publicId()->value(),
            $cartItem->publicId()->value()
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cartItem->publicId()->value())
            ->willReturn($cartItem);

        $this->expectException(CartValidationException::class);

        $this->handler->__invoke($command);
    }

    public function testItRemoveItemFromCartSuccessfully(): void
    {
        $cart = Cart::create();

        $cartItem = CartItem::create(
            cartId: $cart->id(),
            price: CartItemPrice::fromInt(1057),
            quantity: CartItemQuantity::fromInt(1),
            productId: 66
        );

        $command = new RemoveItemFromCartCommand(
            $cart->publicId()->value(),
            $cartItem->publicId()->value()
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cartItem->publicId()->value())
            ->willReturn($cartItem);

        $this->cartItemRepository->expects($this->once())
            ->method('delete')
            ->with($cartItem);

        $this->handler->__invoke($command);
    }
}
