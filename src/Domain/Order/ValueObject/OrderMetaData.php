<?php

namespace App\Domain\Order\ValueObject;

final readonly class OrderMetaData
{
    public function __construct(
        private ?int    $cartId = null,
        private ?string $code = null,
        private ?string $notes = null
    ) {}

    public function toArray(): array
    {
        return [
            'cart_id'   => $this->cartId,
            'card_code' => $this->code,
            'notes'     => $this->notes,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['cart_id'] ?? null,
            $data['cart_code'] ?? false,
            $data['notes'] ?? null
        );
    }
}
