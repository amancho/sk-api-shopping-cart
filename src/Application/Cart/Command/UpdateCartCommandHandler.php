<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\Shared\CartValidator;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartShippingAddress;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class UpdateCartCommandHandler
{
    use CartValidator;

    public function __construct(private CartRepositoryInterface $cartRepository) {}

    /**
     * @throws CartNotFoundException
     * @throws CartInvalidStatusException
     * @throws InvalidUuid
     */
    public function __invoke(UpdateCartCommand $command): void
    {
        $cart = $this->getActiveCart($command->publicId());
        $updatedCart = $this->buildUpdatedCart($cart, $command);

        $this->cartRepository->update($updatedCart);
    }

    /**
     * @throws InvalidUuid
     */
    private function buildUpdatedCart(Cart $original, UpdateCartCommand $command): Cart
    {
        $shippingAddress = CartShippingAddress::build(
            name: $command->name(),
            address: $command->address(),
            city: $command->city(),
            postalCode: $command->postalCode(),
            province: $command->province(),
            country: $command->country()
        );

        return Cart::build(
            id: $original->id()->value(),
            publicId: $original->publicId()->value(),
            code: $original->code()->value(),
            status: CartStatus::PENDING,
            shippingAddress: $shippingAddress,
            shippingEmail: $command->email(),
            shippingPhone: $command->phone() ?? $original->shippingPhone(),
            checkoutId: $original->checkoutId(),
            sessionId: $original->sessionId(),
            userId: $original->userId(),
            orderId: $original->orderId(),
            metadata: $original->metadata()
        );
    }
}
