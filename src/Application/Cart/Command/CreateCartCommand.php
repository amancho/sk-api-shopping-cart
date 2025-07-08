<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

readonly class CreateCartCommand
{
    public function __construct(
        private ?int    $userId = null,
        private ?string $sessionId = null,
    )
    {
    }

    public function userId(): ?int
    {
        return ($this->userId !== null) ? intval($this->userId) : null;
    }

    public function sessionId(): ?string
    {
        return ($this->sessionId !== null) ? strval($this->sessionId) : null;
    }
}
