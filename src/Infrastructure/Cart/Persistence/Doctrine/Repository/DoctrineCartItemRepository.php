<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Repository;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Repository\CartItemRepositoryInterface;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartItemId;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCart;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCartItem;
use App\Infrastructure\Cart\Persistence\Doctrine\Mapper\DoctrineCartItemMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class DoctrineCartItemRepository implements CartItemRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws InvalidUuid
     */
    public function findById(CartItemId $cartItemId): ?CartItem
    {
        $cartItem = $this->em->find(DoctrineCartItem::class, $cartItemId->value());
        if  ($cartItem === null) {
            return null;
        }

        return DoctrineCartItemMapper::toDomain($cartItem);
    }

    /**
     * @throws InvalidUuid
     */
    public function findByPublicId(string $publicId): ?CartItem
    {
        $repository = $this->em->getRepository(DoctrineCartItem::class);
        $cartItem = $repository->findOneBy(['publicId' => $publicId]);

        if  ($cartItem === null) {
            return null;
        }

        return DoctrineCartItemMapper::toDomain($cartItem);
    }

    public function findByCartIdAndProductId(CartId $cartId, int $productId): ?CartItem
    {
        $repository = $this->em->getRepository(DoctrineCartItem::class);
        $cartItem = $repository->findOneBy(['cart' => $cartId->value(), 'productId' => $productId]);

        if  ($cartItem === null) {
            return null;
        }

        return DoctrineCartItemMapper::toDomain($cartItem);
    }

    /**
     * @throws InvalidUuid
     * @throws ORMException
     */
    public function save(CartItem $cartItem): ?CartItem
    {
        $doctrineCart = $this->em->getReference(
            DoctrineCart::class,
            $cartItem->cartId()->value()
        );

        $doctrineCartItem = DoctrineCartItemMapper::fromDomain($cartItem, $doctrineCart);
        $this->em->persist($doctrineCartItem);
        $this->em->flush();
        $this->em->refresh($doctrineCartItem);

        return DoctrineCartItemMapper::toDomain($doctrineCartItem);
    }

    /**
     * @throws ORMException
     */
    public function delete(CartItem $cartItem): void
    {
        $doctrineCartItem = $this->em->getReference(
            DoctrineCartItem::class,
            $cartItem->id()->value()
        );

        $this->em->remove($doctrineCartItem);
        $this->em->flush();
    }
}
