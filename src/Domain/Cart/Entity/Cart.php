<?php declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Domain\Cart\ValueObject\CartCode;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Cart\ValueObject\CartShippingPhone;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\Exception\InvalidUuid;

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

    /**
     * @throws InvalidUuid
     */
    public static function build(
        int $id,
        string $publicId,
        string $code,
        CartStatus $status,
        ?CartShippingAddress $shippingAddress = null,
        ?CartShippingEmail $shippingEmail = null,
        ?CartShippingPhone $shippingPhone = null,
        ?string $checkoutId = null,
        ?int $userId = null,
        ?int $orderId = null,
        ?array $metadata = null,
    ): Cart
    {
        return new self(
            id: CartId::fromInt($id),
            publicId: CartPublicId::fromUuid($publicId),
            code: CartCode::fromCode($code),
            status: $status,
            shippingAddress: $shippingAddress,
            shippingEmail: $shippingEmail,
            shippingPhone: $shippingPhone,
            checkoutId: $checkoutId,
            userId: $userId,
            orderId: $orderId,
            metadata: $metadata,
        );
    }

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
