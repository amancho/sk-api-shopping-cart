<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\AddItemToCartCommand;
use App\Application\Cart\Command\AddItemToCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartProductDuplicatedException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
class AddItemToCartCommandHandlerTest extends TestCase
{
    private AddItemToCartCommandHandler $handler;
    private CartRepositoryInterface $cartRepository;
    private CartItemRepositoryInterface $cartItemRepository;

    protected function setUp(): void
    {
        $this->cartRepository       = $this->createMock(CartRepositoryInterface::class);
        $this->cartItemRepository   = $this->createMock(CartItemRepositoryInterface::class);

        $this->handler = new AddItemToCartCommandHandler(
            $this->cartRepository,
            $this->cartItemRepository
        );
    }

    public function testShouldThrowExceptionIfCartNotFound(): void
    {
        $cardPublicId = Uuid::create()->value();

        $command = new AddItemToCartCommand($cardPublicId, 10.5, 1, 55);

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cardPublicId)
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfProductDuplicated(): void
    {
        $cart       = Cart::create();
        $productId  = 55;

        $cartItem   = CartItem::create(
            $cart->id(),
            CartItemPrice::fromFloat(10.5),
            CartItemQuantity::fromInt(1),
            $productId
        );

        $command = new AddItemToCartCommand($cart->publicId()->value(), 10.5, 1, $productId);

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository->expects($this->once())
            ->method('findByCartIdAndProductId')
            ->with($cart->id(), $productId)
            ->willReturn($cartItem);

        $this->expectException(CartProductDuplicatedException::class);

        $this->handler->__invoke($command);
    }

    public function testAddItemToCartSuccessfully(): void
    {
        $cart       = Cart::create();
        $productId  = 55;

        $cartItem   = CartItem::create(
            $cart->id(),
            CartItemPrice::fromFloat(10.5),
            CartItemQuantity::fromInt(1),
            $productId
        );

        $command = new AddItemToCartCommand($cart->publicId()->value(), 10.5, 1, $productId);

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartItemRepository->expects($this->once())
            ->method('findByCartIdAndProductId')
            ->with($cart->id(), $productId)
            ->willReturn(null);

        $this->cartItemRepository->expects($this->once())
            ->method('save')
            ->willReturn($cartItem);

        $result = $this->handler->__invoke($command);

        $this->assertEquals($cartItem->publicId()->value(), $result);
    }
}
