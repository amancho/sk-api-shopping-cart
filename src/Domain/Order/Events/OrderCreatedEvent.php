<?php declare(strict_types=1);

namespace App\Domain\Order\Events;

use App\Domain\Order\ValueObject\OrderId;
use App\Domain\Order\ValueObject\OrderMetaData;

final readonly class OrderCreatedEvent
{
    public function __construct(
        public OrderId $id,
        public OrderMetaData $metaData
    ) {}
}
