<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartItemPublicId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class UpdateItemFromCartCommandHandler
{
    public function __construct(
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository
    )
    {
    }

    /**
     * @throws CartNotFoundException
     * @throws InvalidUuid
     * @throws CartInvalidStatusException
     * @throws CartItemNotFoundException
     */
    public function __invoke(UpdateItemFromCartCommand $command): void
    {
        $this->checkCart($command->cartPublicId());
        $cartItem = $this->getCartItem($command->cartItemPublicId());
        $updatedCartItem = $this->buildUpdatedCartItem($cartItem, $command);

        if ($cartItem->isEqualTo($updatedCartItem) === false) {
            $this->cartItemRepository->update($updatedCartItem);
        }
    }

    /**
     * @throws CartNotFoundException
     * @throws CartInvalidStatusException
     */
    private function checkCart(CartPublicId $cartPublicId): void
    {
        $cart =$this->cartRepository->findByPublicId($cartPublicId->value());

        if ($cart === null) {
            throw CartNotFoundException::create($cartPublicId->value());
        }

        if (!$cart->isActive()) {
            throw CartInvalidStatusException::create($cart->status());
        }
    }

    /**
     * @throws CartItemNotFoundException
     */
    private function getCartItem(CartItemPublicId $cartItemPublicId): CartItem
    {
        $cartItem = $this->cartItemRepository->findByPublicId($cartItemPublicId->value());

        if ($cartItem === null) {
            throw CartItemNotFoundException::create($cartItemPublicId->value());
        }

        return $cartItem;
    }

    /**
     * @throws InvalidUuid
     */
    private function buildUpdatedCartItem(CartItem $original, UpdateItemFromCartCommand $command): CartItem
    {
        return CartItem::build(
            id: $original->id()->value(),
            publicId: $original->publicId()->value(),
            cartId: $original->cartId()->value(),
            price: $command->price()?->value() ?? $original->price()->value(),
            quantity: $command->quantity()?->value() ?? $original->quantity()->value(),
            productId: $command->productId() ?? $original->productId(),
            name: $command->name() ?? $original->name(),
            color: $command->color() ?? $original->color(),
            size: $command->size() ?? $original->size(),
        );
    }
}
