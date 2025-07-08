<?php declare(strict_types=1);

namespace App\Tests\Application\Cart\Command;

use App\Application\Cart\Command\AddItemToCartCommand;
use App\Domain\Shared\ValueObject\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddItemToCartCommandTest extends TestCase
{

    /**
     * @dataProvider provideInvalidValues
     */
    public function testShouldThrowExceptionIncorrectValue(float $price, int $quantity, int $productId): void
    {
        $cardPublicId  = Uuid::create()->value();
        $this->expectException(InvalidArgumentException::class);

        new AddItemToCartCommand($cardPublicId, $price, $quantity, $productId);
    }

    public static function provideInvalidValues(): array
    {
        return [
            'negative price'     => [-10.0, 1, 55],
            'zero quantity'      => [10.0, 0, 55],
            'negative quantity'  => [10.0, -2, 55],
        ];
    }
}
