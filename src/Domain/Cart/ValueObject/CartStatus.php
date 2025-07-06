<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

enum CartStatus: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case ORDERED = 'ordered';
}

