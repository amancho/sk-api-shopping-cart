<?php declare(strict_types=1);

namespace App\Domain\Shared\Exception;

use Exception;

final class InvalidUuid extends Exception
{
    public static function invalidValue(string $value): self
    {
        return new self(\sprintf('Uuid %s is not valid', $value));
    }
}