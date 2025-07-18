<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\Shared\CartValidator;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartItemNotFoundException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use LogicException;

readonly class RemoveItemFromCartCommandHandler
{
    use CartValidator;

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
     */
    public function __invoke(RemoveItemFromCartCommand $command): void
    {
        $cart = $this->getActiveCart($command->cartPublicId());

        $cartItemPublicId = $command->cartItemPublicId()->value();
        $cartItem = $this->cartItemRepository->findByPublicId($cartItemPublicId);

        if ($cartItem === null) {
            throw CartItemNotFoundException::create($cartItemPublicId);
        }

        if (!$cart->id()->equals($cartItem->cartId())) {
            throw new LogicException('The cart item does not belong to the cart.');
        }

        $this->cartItemRepository->delete($cartItem);
    }
}
