<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCartCommand
{
    #[Assert\Type(type: 'integer')]
    public ?int $userId;

    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Type(type: 'string')]
    public ?string $sessionId;

    public function __construct(
        ?int $userId = null,
        ?string $sessionId = null,
    )
    {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
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
