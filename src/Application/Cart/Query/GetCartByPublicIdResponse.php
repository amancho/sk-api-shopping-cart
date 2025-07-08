<?php declare(strict_types=1);

namespace App\Application\Cart\Query;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;

readonly class GetCartByPublicIdResponse
{
    public function __construct(private Cart $cart) {}

    public function toArray(): array
    {
        return [
            'id'                => $this->cart->publicId()->value(),
            'code'              => $this->cart->code()->value(),
            'status'            => $this->cart->status(),
            'user_id'           => $this->cart->userId(),
            'order_id'          => $this->cart->orderId(),
            'checkout_id'       => $this->cart->checkoutId(),
            'shipping_address'  => $this->cart->shippingAddress()?->toArray(),
            'email'             => $this->cart->shippingEmail()?->value(),
            'phone'             => $this->cart->shippingPhone()?->value(),
            'items'             => $this->getCartItems($this->cart->items())
        ];
    }

    private function getCartItems(?array $items): array
    {
        if ($items === null) {
            return [];
        }

        return array_map(fn(CartItem $item) => $item->toArray(), $items);
    }
}
