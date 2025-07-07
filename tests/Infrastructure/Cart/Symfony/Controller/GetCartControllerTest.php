<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Cart\Symfony\Controller;

use App\Application\Cart\Query\GetCartByPublicIdHandler;
use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\ValueObject\Code;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Cart\Symfony\Controller\GetCartController;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class GetCartControllerTest extends TestCase
{
    private GetCartByPublicIdHandler|MockObject $handler;
    private GetCartController $controller;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->handler = new GetCartByPublicIdHandler($this->cartRepository);

        $this->controller = new GetCartController($this->handler);
    }

    public function testCartSuccessfully(): void
    {
        $cartPublicId = Uuid::asString();
        $cart =  Cart::build(
            id: 1,
            publicId: $cartPublicId,
            code: Code::create('TEST-')->value(),
            status: CartStatus::NEW,
        );

        $this->cartRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->with($cartPublicId)
            ->willReturn($cart);

        $result = $this->controller->show($cartPublicId);

        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
        $content = json_decode($result->getContent(), true);

        $this->assertEquals($cartPublicId, $content['id']);
        $this->assertEquals($cart->code()->value(), $content['code']);
        $this->assertEquals($cart->status()->value, $content['status']);
    }

    public function testShowPlayersClubNotFound(): void
    {
        $cartPublicId = Uuid::asString();

        $this->cartRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->with($cartPublicId)
            ->willReturn(null);

        $result = $this->controller->show($cartPublicId);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatusCode());
        $content = json_decode($result->getContent(), true);
        $this->assertStringContainsString('not found', $content['error']);
    }

    public function testShowPlayersUnexpectedError(): void
    {
        $cartPublicId = Uuid::asString();;

        $this->cartRepository
            ->expects($this->once())
            ->method('findByPublicId')
            ->willThrowException(new RuntimeException('Unexpected error'));

        $result = $this->controller->show($cartPublicId);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result->getStatusCode());
        $content = json_decode($result->getContent(), true);
        $this->assertEquals('Unknown error', $content['error']);
    }
}
