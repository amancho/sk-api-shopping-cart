<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\RemoveItemFromCartCommand;
use App\Domain\Cart\ValueObject\CartItemPublicId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\RemoveItemFromCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class RemoveItemFromCartControllerTest extends TestCase
{
    private MessageBusInterface $bus;

    protected function setUp(): void
    {
        $this->bus = $this->createStub(MessageBusInterface::class);
    }

    public function testItFailsUnknownError(): void
    {
        $this->bus
            ->method('dispatch')
            ->willThrowException(new RuntimeException('Unknown error'));

        $controller = new RemoveItemFromCartController($this->bus);
        $response = $controller->delete(Uuid::asString(), Uuid::asString());

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testItRemoveItemSuccessfully(): void
    {
        $cartPublicId = CartPublicId::create()->value();
        $cartItemPublicId = CartItemPublicId::create()->value();

        $envelope = new Envelope(
            new RemoveItemFromCartCommand(
                cartPublicId: $cartPublicId,
                cartItemPublicId: $cartItemPublicId
            ),
            [new HandledStamp(null, 'RemoveItemFromCartCommandHandler')]
        );

        $this->bus
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new RemoveItemFromCartController($this->bus);
        $response = $controller->delete($cartPublicId, $cartItemPublicId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
