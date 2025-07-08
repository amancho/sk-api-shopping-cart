<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\ValueObject\CartItemPublicId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Shared\Exception\InvalidUuid;

class RemoveItemFromCartCommand
{
    private CartPublicId $cartPublicId;

    private CartItemPublicId $cartItemPublicId;


    /**
     * @throws InvalidUuid
     */
    public function __construct(
        string $cartPublicId,
        string $cartItemPublicId
    )
    {
        $this->cartPublicId = CartPublicId::fromUuid($cartPublicId);
        $this->cartItemPublicId = CartItemPublicId::fromUuid($cartItemPublicId);
    }

    public function cartPublicId(): CartPublicId
    {
        return $this->cartPublicId;
    }

    public function cartItemPublicId(): CartItemPublicId
    {
        return $this->cartItemPublicId;
    }
}
