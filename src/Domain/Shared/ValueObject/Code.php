<?php declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use DateTimeImmutable;

final readonly class Code
{
    public function __construct(private string $code)
    {
    }

    public static function fromString(string $code): self
    {
        return new Code($code);
    }

    public static function create(string $prefix): self
    {
        return new Code(self::generate($prefix));
    }

    private static function generate(string $prefix): string
    {
        $timestamp = (new DateTimeImmutable())->format('YmdHis');
        $random = \random_int(100, 999);

        return \strtoupper($prefix) . $timestamp . '-' . $random;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function value(): string
    {
        return $this->code;
    }
}
