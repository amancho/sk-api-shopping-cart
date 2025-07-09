<?php declare(strict_types=1);

namespace App\Domain\Cart\Service;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartEmptyException;
use App\Domain\Order\Entity\Order;

class CartCheckoutService
{
    /**
     * @throws CartEmptyException
     */
    public function createOrderFromCart(Cart $cart): Order
    {
        if (count($cart->items()) == 0) {
            throw CartEmptyException::create($cart->id());
        }

        $order = Order::create(
            total_amount: $cart->totalAmount(),
            metadata: [
                'cart_id'   => $cart->id()->value(),
                'cart_code' => $cart->code()->value(),
            ],
            shippingAddress: $cart->shippingAddress()?->toArray()
        );

        /** @var CartItem $item */
        foreach ($cart->items() as $item) {
            $order->addItem($item->toArray());
        }

        return $order;
    }
}
