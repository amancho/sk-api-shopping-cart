<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\CreateCartCommand;
use App\Application\Cart\Command\CreateCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartStatus;
use PHPUnit\Framework\TestCase;

class CreateCartCommandHandlerTest extends TestCase
{
    private CreateCartCommandHandler $handler;
    private CartRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CartRepositoryInterface::class);

        $this->handler = new CreateCartCommandHandler(
            $this->repository
        );
    }

    public function testCartCreateSuccessfully(): void
    {
        $cart = Cart::create(
            sessionId : session_id(),
            userId: 6
        );

        $command = new CreateCartCommand(
            userId: $cart->userId(),
            sessionId: $cart->sessionId()
        );

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Cart $actualCart) use ($cart) {
                return $actualCart->userId() === $cart->userId()
                    && $actualCart->sessionId() === $cart->sessionId()
                    && $actualCart->status()->value === CartStatus::NEW->value;
            }))
            ->willReturnCallback(fn(Cart $cart) => $cart);

        $this->handler->__invoke($command);
    }
}
