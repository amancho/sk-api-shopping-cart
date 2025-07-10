<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\RemoveItemFromCartController;
use App\Infrastructure\Cart\Symfony\Controller\UpdateItemFromCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateItemFromCartControllerTest extends TestCase
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

        $controller = new UpdateItemFromCartController($this->bus);
        $request = new Request([], [], [], [], [], [], json_encode(['quantity' => 1]));
        $response = $controller->update($request, Uuid::asString(), Uuid::asString());

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testItFailsInvalidQuantity(): void
    {
        $controller = new UpdateItemFromCartController($this->bus);
        $request = new Request([], [], [], [], [], [], json_encode(['quantity' => 0]));
        $response = $controller->update($request, Uuid::asString(), Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
