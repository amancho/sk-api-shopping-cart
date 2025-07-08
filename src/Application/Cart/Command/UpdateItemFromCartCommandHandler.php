<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
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
        $cartPublicId = $command->cartPublicId()->value();
        $cart = $this->cartRepository->findByPublicId($cartPublicId);
        if ($cart === null) {
            throw CartNotFoundException::create($cartPublicId);
        }

        if (!$cart->isActive()) {
            throw CartInvalidStatusException::create($cart->status());
        }

        $cartItemPublicId = $command->cartItemPublicId()->value();
        $cartItem = $this->cartItemRepository->findByPublicId($cartItemPublicId);

        if ($cartItem === null) {
            throw CartItemNotFoundException::create($cartItemPublicId);
        }

        $cartItem = CartItem::build(
            id: $cartItem->id()->value(),
            publicId: $cartItem->publicId()->value(),
            cartId: $cartItem->cartId()->value(),
            price: $command->price()->value(),
            quantity: $command->quantity()->value(),
            productId:  $command->productId(),
            name: ($command->name() !== null) ? $command->name() : $cartItem->name(),
            color: ($command->color() !== null) ? $command->color() : $cartItem->color(),
            size: ($command->size() !== null) ? $command->size() : $cartItem->size()
        );

        $this->cartItemRepository->update($cartItem);
    }
}
