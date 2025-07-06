<?php declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use DateTimeImmutable;

final class Code
{
    private string $code;

    public function __construct(string $prefix)
    {
        $this->code = $this->generate($prefix);
    }

    public static function create(string $prefix): self
    {
        return new Code($prefix);
    }

    private function generate(string $prefix): string
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
