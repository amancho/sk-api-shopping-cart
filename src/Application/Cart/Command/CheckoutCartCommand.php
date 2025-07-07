<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CheckoutCartCommand
{
    #[Assert\NotNull]
    #[Assert\Uuid(message: 'Invalid UUID format.')]
    private string $publicId;

    #[Assert\NotNull]
    private string $checkoutId;

    public function __construct(
        string $publicId,
        string $checkoutId
    )
    {
        if ($publicId === '' && $checkoutId === '') {
            throw new InvalidArgumentException('public_id and checkout_id must be present');
        }

        $this->publicId = $publicId;
        $this->checkoutId = $checkoutId;
    }

    public function publicId(): string
    {
        return $this->publicId;
    }

    public function checkoutId(): string
    {
        return $this->checkoutId;
    }
}
