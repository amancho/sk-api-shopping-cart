<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CheckoutCartCommand;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\CheckoutCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CheckoutCartControllerTest extends TestCase
{
    private MessageBusInterface $bus;

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->bus = $this->createStub(MessageBusInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testItFailsCheckoutWithInvalidData(): void
    {
        $controller = new CheckoutCartController($this->bus, $this->validator);
        $response = $controller->checkout(new Request(), Uuid::asString());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('Invalid data', $response->getContent());
    }

    public function testItFailsCheckoutWithUnknownError(): void
    {
        $this->bus
            ->method('dispatch')
            ->willThrowException(new RuntimeException('Unknown error'));

        $controller = new CheckoutCartController($this->bus, $this->validator);

        $request = new Request([], [], [], [], [], [], json_encode(['checkoutId' => '0']));
        $response = $controller->checkout($request,  Uuid::asString());

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testItCheckoutCartSuccessfully(): void
    {
        $cartPublicId = Uuid::asString();
        $checkoutId = md5(uniqid());

        $envelope = new Envelope(
            new CheckoutCartCommand($cartPublicId, $checkoutId),
            [new HandledStamp(null, 'CheckoutCartCommandHandler')]
        );

        $this->bus
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new CheckoutCartController($this->bus, $this->validator);

        $requestData = json_encode([
            'checkoutId' => $checkoutId
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);
        $response = $controller->checkout($request, $cartPublicId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
