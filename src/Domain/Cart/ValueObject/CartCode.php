<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use App\Domain\Shared\ValueObject\Code;

class CartCode
{
    const PREFIX = 'CA-';

    private function __construct(private readonly Code $code)
    {
    }

    public static function fromCode(string $code): self
    {
        return new self(Code::fromString($code));
    }

    public static function create(): self
    {
        return new self(Code::create(CartCode::PREFIX));
    }

    public function value(): string
    {
        return (string) $this->code;
    }
}
