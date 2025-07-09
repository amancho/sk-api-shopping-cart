<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use InvalidArgumentException;

readonly class CheckoutCartCommand
{
    public function __construct(
        private string $publicId,
        private string $checkoutId
    )
    {
        if ($publicId === '' && $checkoutId === '') {
            throw new InvalidArgumentException('public_id and checkout_id must be present');
        }
    }

    public function publicId(): string
    {
        return $this->publicId;
    }

    public function checkoutId(): string
    {
        return $this->checkoutId;
    }
}
