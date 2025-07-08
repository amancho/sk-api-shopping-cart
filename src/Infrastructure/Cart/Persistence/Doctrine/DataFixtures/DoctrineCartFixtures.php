<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\DataFixtures;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Infrastructure\Cart\Persistence\Doctrine\Mapper\DoctrineCartItemMapper;
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

        $cartItem = DoctrineCartItemMapper::fromDomain(
            CartItem::create(
                cartId: $cart->getId(),
                price:100,
                quantity: 5,
                productId: 0,
                name: 'Trail runnning t-shirt',
                color: 'blue',
                size: 's'
            ),
            $cart
        );

        $manager->persist($cartItem);
        $manager->flush();
    }
}
