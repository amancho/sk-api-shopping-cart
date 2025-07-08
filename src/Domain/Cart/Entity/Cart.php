<?php declare(strict_types=1);

namespace App\Domain\Cart\Entity;

use App\Domain\Cart\Exception\CartInvalidStatusException;
use App\Domain\Cart\ValueObject\CartCode;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Cart\ValueObject\CartShippingPhone;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\Exception\InvalidUuid;

class Cart
{
    public function __construct(
        private readonly CartId               $id,
        private readonly CartPublicId         $publicId,
        private readonly CartCode             $code,
        private CartStatus                    $status,
        private readonly ?CartShippingAddress $shippingAddress = null,
        private readonly ?CartShippingEmail   $shippingEmail = null,
        private readonly ?CartShippingPhone   $shippingPhone = null,
        private ?string                       $checkoutId = null,
        private readonly ?string              $sessionId = null,
        private readonly ?int                 $userId = null,
        private readonly ?int                 $orderId = null,
        private readonly ?array               $metadata = null,
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
        ?string $sessionId = null,
        ?int $userId = null,
        ?int $orderId = null,
        ?array $metadata = null,
    ): Cart
    {
        return new Cart(
            id: CartId::fromInt($id),
            publicId: CartPublicId::fromUuid($publicId),
            code: CartCode::fromCode($code),
            status: $status,
            shippingAddress: $shippingAddress,
            shippingEmail: $shippingEmail,
            shippingPhone: $shippingPhone,
            checkoutId: $checkoutId,
            sessionId: $sessionId,
            userId: $userId,
            orderId: $orderId,
            metadata: $metadata,
        );
    }

    /**
     * @throws InvalidUuid
     */
    public static function create(
        ?string $sessionId = null,
        ?int $userId = null
    ): Cart
    {
        return new Cart(
            id: CartId::fromInt(0),
            publicId: CartPublicId::create(),
            code: CartCode::create(),
            status: CartStatus::NEW,
            shippingAddress: null,
            shippingEmail: null,
            shippingPhone: null,
            checkoutId: null,
            sessionId: $sessionId,
            userId: $userId,
            orderId: null,
            metadata: null,
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

    public function sessionId(): ?string
    {
        return $this->sessionId;
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

    private function complete(): void
    {
        $this->status = CartStatus::COMPLETED;
    }

    /**
     * @throws CartInvalidStatusException
     */
    public function checkout(string $checkoutId): void
    {
        if (!in_array($this->status(), [CartStatus::PENDING, CartStatus::NEW])) {
            throw CartInvalidStatusException::create($this->status());
        }

        $this->checkoutId = $checkoutId;
        $this->complete();
    }

    /** @return array<CartItem> */
    public function items(): array
    {
        return [];
    }
}
