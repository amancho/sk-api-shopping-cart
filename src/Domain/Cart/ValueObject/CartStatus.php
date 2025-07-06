<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

use InvalidArgumentException;

final class CartStatus
{
    private const VALID_STATUSES = [
        'new',
        'pending',
        'completed',
        'cancelled',
        'expired',
        'ordered'
    ];

    private string $status;

    public function __construct(string $status)
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid status "%s". Allowed statuses are: %s', $status, implode(', ', self::VALID_STATUSES)));
        }

        $this->status = $status;
    }

    public static function fromString(string $value): CartStatus
    {
        return new CartStatus($value);
    }

    public function value(): string
    {
        return $this->status;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}

