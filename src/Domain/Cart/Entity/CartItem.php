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
        private CartItemPrice    $price,
        private CartItemQuantity $quantity,
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
            price: CartItemPrice::fromInt($price),
            quantity: CartItemQuantity::fromInt($quantity),
            productId: $productId,
            name: $name,
            color: $color,
            size: $size
        );
    }

    /**
     * @throws InvalidUuid
     */
    public static function create(
        CartId $cartId,
        CartItemPrice $price,
        CartItemQuantity $quantity,
        ?int $productId,
        ?string $name = null,
        ?string $color = null,
        ?string $size = null
    ): CartItem
    {
        return new CartItem(
            id: CartItemId::fromInt(0),
            publicId: CartItemPublicId::create(),
            cartId: $cartId,
            price: $price,
            quantity: $quantity,
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

    public function price(): CartItemPrice
    {
        return $this->price;
    }

    public function quantity(): CartItemQuantity
    {
        return $this->quantity;
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

    public function total(): float
    {
        return $this->price()->asDecimal() * $this->quantity()->value();
    }

    public function isEqualTo(CartItem $other): bool
    {
        return $this->id()->equals($other->id())
            && $this->publicId()->equals($other->publicId())
            && $this->cartId()->equals($other->cartId())
            && $this->price()->equals($other->price())
            && $this->quantity()->equals($other->quantity())
            && $this->productId() === $other->productId()
            && $this->name() === $other->name()
            && $this->color() === $other->color()
            && $this->size() === $other->size();
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->publicId()->value(),
            'product_id'   => $this->productId(),
            'name'         => $this->name(),
            'color'        => $this->color(),
            'size'         => $this->size(),
            'quantity'     => $this->quantity()->value(),
            'price'        => $this->price()->value(),
            'total'        => $this->total(),
        ];
    }
}
