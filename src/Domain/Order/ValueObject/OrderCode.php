<?php declare(strict_types=1);

namespace App\Domain\Order\ValueObject;

use App\Domain\Shared\ValueObject\Code;

readonly class OrderCode
{
    const PREFIX = 'O-';

    private function __construct(private readonly Code $code)
    {
    }

    public static function fromCode(string $code): self
    {
        return new self(Code::fromString($code));
    }

    public static function create(): self
    {
        return new self(Code::create(OrderCode::PREFIX));
    }

    public function value(): string
    {
        return (string) $this->code;
    }
}
