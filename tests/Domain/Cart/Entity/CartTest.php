<?php declare(strict_types=1);

namespace App\Tests\Domain\Cart\Entity;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\Exception\InvalidUuid;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testItFailsInvalidUuid(): void
    {
        $this->expectException(InvalidUuid::class);

        Cart::build(
            id: 0,
            publicId: '',
            code: 'TEST-001',
            status: CartStatus::NEW
        );
    }
}
