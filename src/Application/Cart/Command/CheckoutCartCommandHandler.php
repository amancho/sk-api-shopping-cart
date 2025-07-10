<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\Shared\CartValidator;
use App\Domain\Cart\Events\CartCheckoutEvent;
use App\Domain\Cart\Exception\CartEmptyException;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\Service\CartCheckoutService;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\Shared\Exception\InvalidUuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

readonly class CheckoutCartCommandHandler
{
    use CartValidator;

    public function __construct(
        private EntityManagerInterface $em,
        private CartCheckoutService $cartCheckoutService,
        private CartRepositoryInterface $cartRepository,
        private OrderRepositoryInterface $orderRepository,
        private MessageBusInterface $eventBus
    ) {}

    /**
     * @param CheckoutCartCommand $command
     * @throws CartEmptyException
     * @throws CartInvalidStatusException
     * @throws CartNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidUuid
     * @throws Throwable
     */
    public function __invoke(CheckoutCartCommand $command): void
    {
        $this->em->getConnection()->beginTransaction();

        try {
            $cart = $this->getActiveCart(CartPublicId::fromUuid($command->publicId()));
            $cart->checkout($command->checkoutId());

            $this->cartRepository->update($cart);

            $order = $this->cartCheckoutService->createOrderFromCart($cart);

            $this->orderRepository->save($order);

            $this->em->flush();
            $this->em->getConnection()->commit();

            $this->eventBus->dispatch(
                new CartCheckoutEvent($cart->id(), $cart->checkoutId())
            );
        } catch (Throwable $e) {
            $this->em->getConnection()->rollBack();
            throw $e;
        }
    }
}
