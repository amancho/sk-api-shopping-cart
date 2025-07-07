<?php declare(strict_types=1);

namespace App\Infrastructure\Shared\Persistence\Repository;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCart;
use App\Infrastructure\Cart\Persistence\Doctrine\Mapper\DoctrineCartMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class DoctrineCartRepository implements CartRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws InvalidUuid
     */
    public function findById(int $id): ?Cart
    {
        $cart = $this->em->find(DoctrineCart::class, $id);
        if  ($cart === null) {
            return null;
        }

        return DoctrineCartMapper::toDomain($cart);
    }

    /**
     * @throws InvalidUuid
     */
    public function findByPublicId(string $publicId): ?Cart
    {
        $repository = $this->em->getRepository(DoctrineCart::class);
        $cart = $repository->findOneBy(['publicId' => $publicId]);

        if  ($cart === null) {
            return null;
        }

        return DoctrineCartMapper::toDomain($cart);
    }
}
