<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Command\CreateCartCommand;
use App\Domain\Cart\Exception\CartValidationException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\CreateCartController;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCartControllerTest extends TestCase
{
    private MessageBusInterface $bus;
    protected function setUp(): void
    {
        $this->bus          = $this->createStub(MessageBusInterface::class);
        $this->validator    = $this->createMock(ValidatorInterface::class);
    }

    public function testItFailsCreateWithInvalidData(): void
    {
        $controller = new CreateCartController($this->bus, $this->validator);
        $response = $controller->create(new Request());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('Invalid data', $response->getContent());
    }

    public function testItFailsCreateWithUnknownError(): void
    {
        $this->bus
            ->method('dispatch')
            ->willThrowException(new RuntimeException('Unknown error'));

        $controller = new CreateCartController($this->bus, $this->validator);

        $request = new Request([], [], [], [], [], [], json_encode(['userId' => 0, 'sessionId' => 0]));
        $response = $controller->create($request);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testItFailsValidation(): void
    {
        $violation = $this->createMock(ConstraintViolation::class);
        $violation->method('getPropertyPath')->willReturn('userId');
        $violation->method('getMessage')->willReturn('This value should not be blank.');

        $violationList = new ConstraintViolationList([$violation]);

        $this->validator->method('validate')->willReturn($violationList);

        $controller = new CreateCartController($this->bus, $this->validator);

        $request = new Request([], [], [], [], [], [], json_encode(['userId' => 'abcd', 'sessionId' => '1']));
        $response = $controller->create($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItCreateCartSuccessfully(): void
    {
        $expectedCartId = Uuid::create();
        $sessionId = md5(uniqid());

        $envelope = new Envelope(
            new CreateCartCommand(null, $sessionId),
            [new HandledStamp($expectedCartId->value(), 'CreateCartHandler')]
        );

        $this->bus
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new CreateCartController($this->bus, $this->validator);

        $requestData = json_encode([
            'userId'    => null,
            'sessionId' => $sessionId
        ]);

        $request = new Request([], [], [], [], [], [], $requestData);
        $response = $controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertStringContainsString($expectedCartId->value(), $response->getContent());
    }
}
