<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class CreateCartCommandHandler
{
    public function __construct(private CartRepositoryInterface $repository)
    {
    }

    /**
     * @throws InvalidUuid
     */
    public function __invoke(CreateCartCommand $command): string
    {
        $cart = Cart::create(
            sessionId: $command->sessionId(),
            userId: $command->userId()
        );

        $cartCreated = $this->repository->save($cart);

        return $cartCreated->publicId()->value();
    }
}
