<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\Shared\CartValidator;
use App\Domain\Cart\Events\CartCheckoutEvent;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartPublicId;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CheckoutCartCommandHandler
{
    use CartValidator;

    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private MessageBusInterface $eventBus
    )
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws CartInvalidStatusException
     * @throws CartNotFoundException
     */
    public function __invoke(CheckoutCartCommand $command): void
    {
        $cart = $this->getActiveCart(CartPublicId::fromUuid($command->publicId()));

        $cart->checkout($command->checkoutId());

        $this->cartRepository->update($cart);

        $this->eventBus->dispatch(
            new CartCheckoutEvent($cart->id(), $cart->checkoutId())
        );
    }
}
