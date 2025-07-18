<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\Shared\CartValidator;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Exception\CartProductDuplicatedException;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class AddItemToCartCommandHandler
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
     * @throws InvalidUuid
     * @throws CartProductDuplicatedException
     * @throws CartInvalidStatusException
     */
    public function __invoke(AddItemToCartCommand $command): string
    {
        $cart = $this->getActiveCart($command->cartPublicId());

        $cartItem = $this->cartItemRepository->findByCartIdAndProductId(
            $cart->id(),
            $command->productId()
        );

        if ($cartItem !== null) {
            throw CartProductDuplicatedException::create($command->productId());
        }

        $cartItem = CartItem::create(
            cartId: $cart->id(),
            price: $command->price(),
            quantity: $command->quantity(),
            productId: $command->productId(),
            name: $command->name(),
            color: $command->color(),
            size: $command->size()
        );

        $cartItemCreated = $this->cartItemRepository->save($cartItem);

        return $cartItemCreated->publicId()->value();
    }
}
