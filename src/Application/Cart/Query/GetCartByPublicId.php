<?php declare(strict_types=1);

namespace App\Application\Cart\Query;

final readonly class GetCartByPublicId
{
    public function __construct(
        public string $publicId
    ) {}

    public function publicId(): string
    {
        return $this->publicId;
    }
}
