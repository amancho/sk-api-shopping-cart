<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Repository;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Exception\CartNotFoundException;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCart;
use App\Infrastructure\Cart\Persistence\Doctrine\Mapper\DoctrineCartMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class DoctrineCartRepository implements CartRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws InvalidUuid
     */
    public function findById(CartId $cartId): ?Cart
    {
        $cart = $this->em->find(DoctrineCart::class, $cartId->value());
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

    /**
     * @throws InvalidUuid
     */
    public function save(Cart $cart): ?Cart
    {
        $doctrineCart = DoctrineCartMapper::fromDomain($cart);
        $this->em->persist($doctrineCart);
        $this->em->flush();

        return DoctrineCartMapper::toDomain($doctrineCart);
    }

    /**
     * @throws OptimisticLockException
     * @throws CartNotFoundException
     * @throws ORMException
     * @throws InvalidUuid
     */
    public function update(Cart $cart): ?Cart
    {
        $doctrineCart = $this->em->find(DoctrineCart::class, $cart->id()->value());

        if ($doctrineCart === null) {
            throw CartNotFoundException::createFromId($cart->id());
        }

        $doctrineCart->setStatus($cart->status()->value);
        $doctrineCart->setUserId($cart->userId());
        $doctrineCart->setOrderId($cart->orderId());
        $doctrineCart->setSessionId($cart->sessionId());
        $doctrineCart->setMetadata($cart->metadata());
        $doctrineCart->setCheckoutId($cart->checkoutId());

        $doctrineCart->setShippingName($cart->shippingAddress()?->name());
        $doctrineCart->setShippingAddress($cart->shippingAddress()?->address());
        $doctrineCart->setShippingCity($cart->shippingAddress()?->city());
        $doctrineCart->setShippingPostalCode($cart->shippingAddress()?->postalCode());
        $doctrineCart->setShippingProvince($cart->shippingAddress()?->province());
        $doctrineCart->setShippingCountry($cart->shippingAddress()?->country());
        $doctrineCart->setShippingEmail($cart->shippingEmail()?->value());
        $doctrineCart->setShippingPhone($cart->shippingPhone()?->value());

        $this->em->flush();

        return DoctrineCartMapper::toDomain($doctrineCart);
    }
}
