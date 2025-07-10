<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\UpdateCartCommand;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\UpdateCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class UpdateCartControllerTest extends TestCase
{
    private MessageBusInterface $bus;


    protected function setUp(): void
    {
        $this->bus = $this->createStub(MessageBusInterface::class);
    }

    public function testItFailsUpdateWithInvalidData(): void
    {
        $controller = new UpdateCartController($this->bus);
        $response = $controller->update(new Request(), Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('Invalid data', $response->getContent());
    }

    public function testItFailsUpdateWithUnknownError(): void
    {
        $this->bus
            ->method('dispatch')
            ->willThrowException(new RuntimeException('Unknown error'));

        $controller = new UpdateCartController($this->bus);

        $request = new Request([], [], [], [], [], [], json_encode(['email' => '0']));
        $response = $controller->update($request,  Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItUpdateCartSuccessfully(): void
    {
        $cartPublicId = CartPublicId::create();

        $envelope = new Envelope(
            new UpdateCartCommand(
                name: 'Otilio Gotera',
                address: 'Ruel del Percebe, 13',
                city: 'Madrid',
                publicId: $cartPublicId,
                email: CartShippingEmail::fromString('ogotera@dev.io'),
                country: 'Spain'
            ),
            [new HandledStamp(Uuid::asString(), 'AddItemToCartCommandHandler')]
        );

        $this->bus
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new UpdateCartController($this->bus);

        $requestData = json_encode([
                'name'      => 'Otilio Gotera',
                'address'   => 'Ruel del Percebe, 13',
                'city'      => 'Madrid',
                'email'     => 'ogotera@dev.io',
                'country'   => 'Spain'
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);
        $response = $controller->update($request, $cartPublicId->value());

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
