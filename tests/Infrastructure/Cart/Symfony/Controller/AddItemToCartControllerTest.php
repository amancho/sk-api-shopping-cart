<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\AddItemToCartCommand;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\AddItemToCartController;
use App\Infrastructure\Cart\Symfony\Controller\CheckoutCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class AddItemToCartControllerTest extends TestCase
{
    private MessageBusInterface $bus;


    protected function setUp(): void
    {
        $this->bus = $this->createStub(MessageBusInterface::class);
    }

    public function testItFailsCheckoutWithInvalidData(): void
    {
        $controller = new AddItemToCartController($this->bus);
        $response = $controller->add(new Request(), Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('Invalid data', $response->getContent());
    }

    public function testItFailsAddProductWithUnknownError(): void
    {
        $this->bus
            ->method('dispatch')
            ->willThrowException(new RuntimeException('Unknown error'));

        $controller = new AddItemToCartController($this->bus);

        $request = new Request([], [], [], [], [], [], json_encode(['quantity' => '0']));
        $response = $controller->add($request,  Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItAddItemToCartSuccessfully(): void
    {
        $cartPublicId = Uuid::asString();

        $envelope = new Envelope(
            new AddItemToCartCommand($cartPublicId, 10.5, 1, 55),
            [new HandledStamp(Uuid::asString(), 'AddItemToCartCommandHandler')]
        );

        $this->bus
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new AddItemToCartController($this->bus);

        $requestData = json_encode([
            'price' => 10.5,
            'quantity' => 1,
            'product_id' => 55,
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);
        $response = $controller->add($request, $cartPublicId);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertStringContainsString($cartPublicId, $response->getContent());
    }
}
