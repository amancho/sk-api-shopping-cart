<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartValidationException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;

readonly class RemoveItemFromCartCommandHandler
{
    public function __construct(
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository
    )
    {
    }

    /**
     * @throws CartNotFoundException
     * @throws CartInvalidStatusException
     * @throws CartItemNotFoundException
     * @throws CartValidationException
     */
    public function __invoke(RemoveItemFromCartCommand $command): void
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

        if ($cart->id() !== $cartItem->cartId()) {
            throw CartValidationException::create(['The cart item does not belong to the cart.']);
        }

        $this->cartItemRepository->delete($cartItem);
    }
}
