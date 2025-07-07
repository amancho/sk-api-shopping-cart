<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\Events\CartCheckoutEvent;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CheckoutCartCommandHandler
{
    public function __construct(
        private CartRepositoryInterface $repository,
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
        $cart = $this->repository->findByPublicId($command->publicId());
        if ($cart === null) {
            throw CartNotFoundException::create($command->publicId());
        }

        $cart->checkout($command->checkoutId());

        $this->repository->update($cart);

        $this->eventBus->dispatch(
            new CartCheckoutEvent($cart->id(), $cart->checkoutId())
        );
    }
}
