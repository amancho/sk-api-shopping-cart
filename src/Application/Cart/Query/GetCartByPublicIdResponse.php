<?php declare(strict_types=1);

namespace App\Application\Cart\Query;

use App\Domain\Cart\Entity\Cart;

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
        ];
    }
}
