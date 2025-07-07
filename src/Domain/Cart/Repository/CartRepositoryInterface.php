<?php declare(strict_types=1);

namespace App\Domain\Cart\Repository;

use App\Domain\Cart\Entity\Cart;

interface CartRepositoryInterface
{
    public function findByPublicId(string $publicId): ?Cart;
}
