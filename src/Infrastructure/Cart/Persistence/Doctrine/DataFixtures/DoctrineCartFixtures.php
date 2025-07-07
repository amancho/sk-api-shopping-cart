<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\DataFixtures;

use App\Domain\Cart\Entity\Cart;
use App\Infrastructure\Cart\Persistence\Doctrine\Mapper\DoctrineCartMapper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DoctrineCartFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cart = DoctrineCartMapper::fromDomain(Cart::create());

        $manager->persist($cart);
        $manager->flush();
    }
}
