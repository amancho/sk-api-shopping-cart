<?php declare(strict_types=1);

namespace App\Infrastructure\Order\Persistence\Doctrine\Mapper;

use App\Domain\Order\Entity\Order;
use App\Infrastructure\Order\Persistence\Doctrine\Entity\DoctrineOrder;

class DoctrineOrderMapper
{
    public static function fromDomain(Order $order): DoctrineOrder
    {
        $doctrineOrder = new DoctrineOrder();

        $doctrineOrder->setId($order->id()->value());
        $doctrineOrder->setCode($order->code()->value());
        $doctrineOrder->setStatus($order->status()->value);
        $doctrineOrder->setUserId($order->userId());
        $doctrineOrder->setTotalAmount($order->totalAmount()->value());
        $doctrineOrder->setMetadata($order->metadata()->toArray());

        if ($order->shippingAddress() !== null) {
            $doctrineOrder->setShippingAddress(
                $order->shippingAddress()->toArray()
            );
        }

        return $doctrineOrder;
    }

    public static function toDomain(DoctrineOrder $doctrineOrder): Order
    {
        return Order::build(
            id: $doctrineOrder->getId(),
            total_amount: $doctrineOrder->getTotalAmount(),
            code: $doctrineOrder->getCode(),
            status: $doctrineOrder->getStatus(),
            shippingAddress: $doctrineOrder->getShippingAddress(),
            metadata: $doctrineOrder->getMetadata(),
            userId: $doctrineOrder->getUserId()
        );
    }
}
