<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\Service;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartEmptyException;
use App\Domain\Cart\Service\CartCheckoutService;
use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use PHPUnit\Framework\TestCase;

class CartCheckoutServiceTest extends TestCase
{
    public function testItFailsCreateOrderFromEmptyCart(): void
    {
        $this->expectException(CartEmptyException::class);

        $carCheckoutService = new CartCheckoutService();
        $carCheckoutService->createOrderFromCart(Cart::create());
    }

    public function testItCreateOrderFromCartSuccesfully(): void
    {
        $cart = Cart::create();

        $cart->addItem(CartItem::create(
            cartId: $cart->id(),
            price: CartItemPrice::fromFloat(10.5),
            quantity:  CartItemQuantity::fromInt(1),
            productId: 55
        ));

        $carCheckoutService = new CartCheckoutService();
        $order = $carCheckoutService->createOrderFromCart($cart);

        $this->assertEquals($order->totalAmount()->value(), $cart->totalAmount());
        $this->assertEquals($order->metadata()->code(), $cart->code()->value());
        $this->assertEquals($order->metadata()->cartId(), $cart->id()->value());
    }
}
