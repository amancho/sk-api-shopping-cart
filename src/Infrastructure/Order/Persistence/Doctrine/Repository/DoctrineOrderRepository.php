<?php declare(strict_types=1);

namespace App\Infrastructure\Order\Persistence\Doctrine\Repository;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Infrastructure\Order\Persistence\Doctrine\Mapper\DoctrineOrderMapper;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Order $order): ?Order
    {
        $doctrineOrder = DoctrineOrderMapper::fromDomain($order);
        $this->em->persist($doctrineOrder);
        $this->em->flush();

        return DoctrineOrderMapper::toDomain($doctrineOrder);
    }
}
