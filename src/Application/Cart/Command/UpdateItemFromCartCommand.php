<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemPublicId;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class UpdateItemFromCartCommand
{
    public function __construct(
        private CartItemPublicId  $cartItemPublicId,
        private CartPublicId      $cartPublicId,
        private ?CartItemPrice    $price = null,
        private ?CartItemQuantity $quantity = null,
        private ?int              $productId = null,
        private ?string           $name = null,
        private ?string           $color = null,
        private ?string           $size = null,
    ) {}

    /**
     * @throws InvalidUuid
     */
    public static function fromPrimitives(
        string  $cartItemPublicId,
        string  $cartPublicId,
        ?float  $price = null,
        ?int    $quantity = null,
        ?int    $productId = null,
        ?string $name = null,
        ?string $color = null,
        ?string $size = null,
    ): self {
        return new self(
            cartItemPublicId:  CartItemPublicId::fromUuid($cartItemPublicId),
            cartPublicId: CartPublicId::fromUuid($cartPublicId),
            price: $price !== null ? CartItemPrice::fromFloat($price) : null,
            quantity:  $quantity !== null ? CartItemQuantity::fromInt($quantity) : null,
            productId: $productId,
            name: $name,
            color: $color,
            size: $size,
        );
    }

    public function cartPublicId(): CartPublicId
    {
        return $this->cartPublicId;
    }

    public function cartItemPublicId(): CartItemPublicId
    {
        return $this->cartItemPublicId;
    }

    public function price(): ?CartItemPrice {
        return $this->price;
    }

    public function productId(): ?int
    {
        return $this->productId;
    }

    public function name(): ?string {
        return $this->name;
    }

    public function color(): ?string {
        return $this->color;
    }

    public function size(): ?string {
        return $this->size;
    }

    public function quantity(): ?CartItemQuantity {
        return $this->quantity;
    }
}
