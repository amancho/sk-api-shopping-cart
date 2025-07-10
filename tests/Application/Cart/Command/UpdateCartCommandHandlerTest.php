<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\UpdateCartCommand;
use App\Application\Cart\Command\UpdateCartCommandHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
class UpdateCartCommandHandlerTest extends TestCase
{
    private UpdateCartCommandHandler $handler;
    private CartRepositoryInterface $cartRepository;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);

        $this->handler = new UpdateCartCommandHandler($this->cartRepository);
    }

    public function testShouldThrowExceptionIfCartNotFound(): void
    {
        $cart = Cart::create();

        $command = UpdateCartCommand::fromPrimitives(
            publicId: $cart->publicId()->value(),
            name: 'Customer Test Name',
            address: 'Avenida de la Castellana, 55',
            city: 'Madrid',
            email: 'test@dev.io',
            country: 'ES',
            postal_code: '28001', province: null
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);

        $this->handler->__invoke($command);
    }

    public function testShouldThrowExceptionIfCartNotAvailable(): void
    {
        $cart = Cart::build(
            id: 0,
            publicId: Uuid::create()->value(),
            code: 'TEST-002',
            status: CartStatus::COMPLETED,
        );

        $command = UpdateCartCommand::fromPrimitives(
            publicId: $cart->publicId()->value(),
            name: 'Customer Test Name',
            address: 'Avenida de la Castellana, 55',
            city: 'Madrid',
            email: 'test@dev.io',
            country: 'ES',
            postal_code: '28001', province: null
        );

        $this->cartRepository->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->expectException(CartInvalidStatusException::class);

        $this->handler->__invoke($command);
    }

    public function testUpdateCartItemSuccessfully(): void
    {
        $cart = Cart::build(
            id: 0,
            publicId: Uuid::create()->value(),
            code: 'TEST-002',
            status: CartStatus::PENDING,
        );

        $command = UpdateCartCommand::fromPrimitives(
            publicId: $cart->publicId()->value(),
            name: 'Customer Test Name',
            address: 'Avenida de la Castellana, 55',
            city: 'Madrid',
            email: 'test@dev.io',
            country: 'ES',
            postal_code: '28001'
        );

        $this->cartRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->with($cart->publicId()->value())
            ->willReturn($cart);

        $this->cartRepository
            ->expects($this->once())
            ->method('update')
            ->willReturn($cart);

        $this->handler->__invoke($command);
    }
}
