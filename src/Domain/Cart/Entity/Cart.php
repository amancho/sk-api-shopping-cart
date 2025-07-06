<?php declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Domain\Cart\ValueObject\CartCode;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Cart\ValueObject\CartShippingPhone;
use App\Domain\Cart\ValueObject\CartStatus;

readonly class Cart
{
    public function __construct(
        private CartId $id,
        private CartPublicId $publicId,
        private CartCode $code,
        private CartStatus $status,
        private ?CartShippingAddress $shippingAddress = null,
        private ?CartShippingEmail $shippingEmail = null,
        private ?CartShippingPhone $shippingPhone = null,
        private ?string $checkoutId = null,
        private ?int $userId = null,
        private ?int $orderId = null,
        private ?array $metadata = null,
    ) {}

    public function id(): CartId
    {
        return $this->id;
    }

    public function publicId(): CartPublicId
    {
        return $this->publicId;
    }

    public function code(): CartCode
    {
        return $this->code;
    }

    public function status(): CartStatus
    {
        return $this->status;
    }

    public function shippingAddress(): ?CartShippingAddress
    {
        return $this->shippingAddress;
    }

    public function shippingPhone(): ?CartShippingPhone
    {
        return $this->shippingPhone;
    }

    public function shippingEmail(): ?CartShippingEmail
    {
        return $this->shippingEmail;
    }

    public function checkoutId(): ?string
    {
        return $this->checkoutId;
    }

    public function userId(): ?int
    {
        return $this->userId;
    }

    public function orderId(): ?int
    {
        return $this->orderId;
    }

    public function meta(): ?array
    {
        return $this->metadata;
    }
}
