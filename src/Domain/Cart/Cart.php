<?php declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Cart\ValueObject\CartCode;
use App\Domain\Cart\ValueObject\CartPublicId;

readonly class Cart
{
    public function __construct(
        private CartPublicId $publicId,
        private CartCode $code,
    ) {}

    public function publicId(): CartPublicId
    {
        return $this->publicId;
    }

    public function code(): CartCode
    {
        return $this->code;
    }
}
