<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\CheckoutCartCommand;
use App\Application\Cart\Command\CheckoutCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Events\CartCheckoutEvent;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckoutCartCommandHandlerTest extends TestCase
{
    private CheckoutCartCommandHandler $handler;
    private CartRepositoryInterface $repository;
    private MessageBusInterface $bus;

    protected function setUp(): void
    {
        $this->repository   = $this->createMock(CartRepositoryInterface::class);
        $this->bus          = $this->createMock(MessageBusInterface::class);

        $this->handler = new CheckoutCartCommandHandler(
            $this->repository,
            $this->bus
        );
    }

    public function testShouldThrowExceptionIfCartNotFound(): void
    {
        $checkoutId     = md5('1234567890');
        $cardPublicId   = Uuid::create()->value();

        $command = new CheckoutCartCommand($cardPublicId, 'test-public-id' , $checkoutId);

        $this->repository->expects($this->once())
            ->method('findByPublicId')
            ->with($command->publicId())
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfCartStatusNotAllowed(): void
    {
        $checkoutId = md5('1234567890');

        $cart = Cart::build(
            id: CartId::fromInt(\random_int(100, 999))->value(),
            publicId: Uuid::create()->value(),
            code: 'TEST-002',
            status: CartStatus::ORDERED,
            checkoutId: $checkoutId,
        );

        $command = new CheckoutCartCommand(
            publicId: $cart->publicId()->value(),
            checkoutId: $checkoutId
        );

        $this->repository->expects($this->once())
            ->method('findByPublicId')
            ->with($command->publicId())
            ->willReturn($cart);

        $this->expectException(CartInvalidStatusException::class);

        $this->handler->__invoke($command);
    }

    public function testCartCheckoutSuccessfully(): void
    {
        $cart = Cart::create();

        $command = new CheckoutCartCommand(
            publicId: $cart->publicId()->value(),
            checkoutId: md5('1234567890')
        );

        $this->repository->expects($this->once())
            ->method('findByPublicId')
            ->with($command->publicId())
            ->willReturn($cart);

        $this->bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(CartCheckoutEvent::class))
            ->willReturn(new Envelope(new CartCheckoutEvent($cart->id(), $command->checkoutId())));

        $this->handler->__invoke($command);
    }
}
