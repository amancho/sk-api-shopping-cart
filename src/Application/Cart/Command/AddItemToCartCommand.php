<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\ValueObject\CartItemPrice;
use App\Domain\Cart\ValueObject\CartItemQuantity;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Shared\Exception\InvalidUuid;

class AddItemToCartCommand
{
    private CartPublicId $cartPublicId;
    private CartItemPrice $price;
    private CartItemQuantity $quantity;

    /**
     * @throws InvalidUuid
     */
    public function __construct(
        string $cartPublicId,
        float $price,
        int $quantity,
        private readonly int $productId,
        private readonly ?string $name = null,
        private readonly ?string $color = null,
        private readonly ?string $size = null,
    )
    {
        $this->cartPublicId = CartPublicId::fromUuid($cartPublicId);
        $this->price = CartItemPrice::fromFloat($price);
        $this->quantity = CartItemQuantity::fromInt($quantity);
    }

    public function cartPublicId(): CartPublicId
    {
        return $this->cartPublicId;
    }

    public function price(): CartItemPrice {
        return $this->price;
    }

    public function productId(): int
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

    public function quantity(): CartItemQuantity {
        return $this->quantity;
    }
}
