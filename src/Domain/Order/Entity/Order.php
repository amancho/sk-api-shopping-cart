<?php declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Domain\Order\ValueObject\OrderCode;
use App\Domain\Order\ValueObject\OrderId;
use App\Domain\Order\ValueObject\OrderMetaData;
use App\Domain\Order\ValueObject\OrderShippingAddress;
use App\Domain\Order\ValueObject\OrderStatus;
use App\Domain\Order\ValueObject\OrderTotalAmount;

class Order
{
    private array $items = [];

    public function __construct(
        private readonly OrderId                $id,
        private readonly OrderCode              $code,
        private OrderStatus                     $status,
        private readonly OrderTotalAmount       $total_amount,
        private readonly ?OrderShippingAddress  $shippingAddress = null,
        private readonly ?OrderMetaData         $metadata = null,
        private readonly ?int                   $userId = null,
    ) {}

    public static function build(
        int $id,
        int $total_amount,
        string $code,
        string $status,
        ?array $shippingAddress = null,
        ?array $metadata = null,
        ?int $userId = null,
    ): Order
    {
        return new Order(
            id: OrderId::fromInt($id),
            code: OrderCode::fromCode($code),
            status: OrderStatus::from($status),
            total_amount: OrderTotalAmount::fromInt($total_amount),
            shippingAddress: OrderShippingAddress::fromArray($shippingAddress),
            metadata: OrderMetaData::fromArray($metadata),
            userId: $userId
        );
    }

    public static function create(
        int $total_amount,
        array $metadata
    ): Order {
        return new Order(
            id: OrderId::fromInt(0),
            code: OrderCode::create(),
            status: OrderStatus::NEW,
            total_amount: OrderTotalAmount::fromInt($total_amount),
            metadata: OrderMetaData::fromArray($metadata)
        );
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function code(): OrderCode
    {
        return $this->code;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function totalAmount(): OrderTotalAmount
    {
        return $this->total_amount;
    }

    public function metadata(): OrderMetaData
    {
        return $this->metadata;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [OrderStatus::PENDING, OrderStatus::NEW]);
    }

    public function shippingAddress(): ?OrderShippingAddress
    {
        return $this->shippingAddress;
    }

    public function userId(): ?int
    {
        return $this->userId;
    }

    public function items(): ?array
    {
        return $this->items;
    }
}
