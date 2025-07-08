<?php declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartItemId;
use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemPublicId;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class CartItem
{
    public function __construct(
        private CartItemId       $id,
        private CartItemPublicId $publicId,
        private CartId           $cartId,
        private CartItemPrice    $cartItemPrice,
        private CartItemQuantity $cartItemQuantity,
        private ?int             $productId = null,
        private ?string          $name = null,
        private ?string          $color = null,
        private ?string          $size = null
    ) {}

    /**
     * @throws InvalidUuid
     */
    public static function build(
        int $id,
        string $publicId,
        int $cartId,
        int $price,
        int $quantity,
        ?int $productId,
        ?string $name = null,
        ?string $color = null,
        ?string $size = null
    ): CartItem
    {
        return new CartItem(
            id: CartItemId::fromInt($id),
            publicId: CartItemPublicId::fromUuid($publicId),
            cartId: CartId::fromInt($cartId),
            cartItemPrice: CartItemPrice::fromInt($price),
            cartItemQuantity: CartItemQuantity::fromInt($quantity),
            productId: $productId,
            name: $name,
            color: $color,
            size: $size
        );
    }

    public function id(): CartItemId
    {
        return $this->id;
    }

    public function publicId(): CartItemPublicId
    {
        return $this->publicId;
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function cartItemPrice(): CartItemPrice
    {
        return $this->cartItemPrice;
    }

    public function cartItemQuantity(): CartItemQuantity
    {
        return $this->cartItemQuantity;
    }

    public function productId(): ?int
    {
        return $this->productId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function color(): ?string
    {
        return $this->color;
    }

    public function size(): ?string
    {
        return $this->size;
    }
}
