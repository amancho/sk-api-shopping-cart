<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Mapper;

use App\Domain\Cart\Entity\CartItem;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCart;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCartItem;

class DoctrineCartItemMapper
{
    public static function fromDomain(CartItem $cartItem, DoctrineCart $doctrineCart): DoctrineCartItem
    {
        $doctrineCartItem = new DoctrineCartItem();

        $doctrineCartItem->setCart($doctrineCart);

        $doctrineCartItem->setId($cartItem->id()->value());
        $doctrineCartItem->setPublicId($cartItem->publicId()->value());

        $doctrineCartItem->setQuantity($cartItem->quantity()->value());
        $doctrineCartItem->setPrice($cartItem->price()->value());

        $doctrineCartItem->setProductId($cartItem->productId());
        $doctrineCartItem->setName($cartItem->name());
        $doctrineCartItem->setColor($cartItem->color());
        $doctrineCartItem->setSize($cartItem->size());

        return $doctrineCartItem;
    }

    /**
     * @throws InvalidUuid
     */
    public static function toDomain(DoctrineCartItem $doctrineCartItem): CartItem
    {
        return CartItem::build(
            id: $doctrineCartItem->getId(),
            publicId: $doctrineCartItem->getPublicId(),
            cartId: $doctrineCartItem->getCart()->getId(),
            price: $doctrineCartItem->getPrice(),
            quantity: $doctrineCartItem->getQuantity(),
            productId: $doctrineCartItem->getProductId(),
            name: $doctrineCartItem->getName(),
            color: $doctrineCartItem->getColor(),
            size: $doctrineCartItem->getSize()
        );
    }
}
