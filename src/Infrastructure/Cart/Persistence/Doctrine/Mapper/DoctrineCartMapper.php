<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Mapper;

use App\Domain\Cart\Entity\Cart;
use App\Domain\Cart\Entity\CartItem;
use App\Domain\Cart\Entity\CartShippingAddress;
use App\Domain\Cart\ValueObject\CartCode;
use App\Domain\Cart\ValueObject\CartId;
use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Cart\ValueObject\CartShippingPhone;
use App\Domain\Cart\ValueObject\CartStatus;
use App\Domain\Shared\Exception\InvalidUuid;
use App\Infrastructure\Cart\Persistence\Doctrine\Entity\DoctrineCart;

class DoctrineCartMapper
{
    public static function fromDomain(Cart $cart): DoctrineCart
    {
        $doctrineCart = new DoctrineCart();

        $doctrineCart->setId($cart->id()->value());
        $doctrineCart->setPublicId($cart->publicId()->value());
        $doctrineCart->setCode($cart->code()->value());
        $doctrineCart->setStatus($cart->status()->value);

        if ($cart->shippingAddress() !== null) {
            $doctrineCart->setShippingName($cart->shippingAddress()->name());
            $doctrineCart->setShippingAddress($cart->shippingAddress()->address());
            $doctrineCart->setShippingCity($cart->shippingAddress()->city());
            $doctrineCart->setShippingPostalCode($cart->shippingAddress()->postalCode());
            $doctrineCart->setShippingProvince($cart->shippingAddress()->province());
            $doctrineCart->setShippingCountry($cart->shippingAddress()->country());
        }

        if ($cart->shippingEmail() !== null) {
            $doctrineCart->setShippingEmail($cart->shippingEmail()->value());
        }

        if ($cart->shippingPhone() !== null) {
            $doctrineCart->setShippingPhone($cart->shippingPhone()->value());
        }

        $doctrineCart->setCheckoutId($cart->checkoutId());
        $doctrineCart->setUserId($cart->userId());
        $doctrineCart->setOrderId($cart->orderId());
        $doctrineCart->setMetadata($cart->meta());

        return $doctrineCart;
    }

    /**
     * @throws InvalidUuid
     */
    public static function toDomain(DoctrineCart $doctrineCart): Cart
    {
        $shippingAddress = CartShippingAddress::build(
            name: $doctrineCart->getShippingName(),
            address: $doctrineCart->getShippingName(),
            city: $doctrineCart->getShippingCity(),
            postalCode: $doctrineCart->getShippingPostalCode(),
            province: $doctrineCart->getShippingProvince(),
            country: $doctrineCart->getShippingCountry(),
        );

        $cart = Cart::build(
            id: $doctrineCart->getId(),
            publicId: $doctrineCart->getPublicId(),
            code: $doctrineCart->getCode(),
            status: CartStatus::tryFrom($doctrineCart->getStatus()),
            shippingAddress: $shippingAddress,
            shippingEmail: $doctrineCart->getShippingEmail() ? CartShippingEmail::fromString($doctrineCart->getShippingEmail()) : null,
            shippingPhone: $doctrineCart->getShippingPhone() ? CartShippingPhone::fromString($doctrineCart->getShippingPhone()) : null,
        );

        $cart->addItems(self::mapItems($doctrineCart));

        return $cart;
    }

    /**
     * @return CartItem[]
     * @throws InvalidUuid
     */
    private static function mapItems(DoctrineCart $doctrineCart): array
    {
        $items = [];
        foreach ($doctrineCart->getItems() as $doctrineCartItem) {
            $items[] = DoctrineCartItemMapper::toDomain($doctrineCartItem);
        }

        return $items;
    }
}
