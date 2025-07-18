<?php declare(strict_types=1);

namespace App\Domain\Cart\ValueObject;

readonly class CartShippingAddress
{
    public function __construct(
        private ?string $name = null,
        private ?string $address = null,
        private ?string $city = null,
        private ?string $postalCode = null,
        private ?string $province = null,
        private ?string $country = null
    ) {}

    public static function build(
         ?string $name = null,
         ?string $address = null,
         ?string $city = null,
         ?string $postalCode = null,
         ?string $province = null,
         ?string $country = null
    ): CartShippingAddress {
        return new CartShippingAddress(
            name: $name,
            address: $address,
            city: $city,
            postalCode: $postalCode,
            province: $province,
            country: $country
        );
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function address(): ?string
    {
        return $this->address;
    }

    public function city(): ?string
    {
        return $this->city;
    }

    public function postalCode(): ?string
    {
        return $this->postalCode;
    }

    public function province(): ?string
    {
        return $this->province;
    }

    public function country(): ?string
    {
        return $this->country;
    }

    public function toArray(): array
    {
        return [
            'name'          => $this->name,
            'address'       => $this->address,
            'city'          => $this->city,
            'postal_code'   => $this->postalCode,
            'province'      => $this->province,
            'country'       => $this->country,
        ];
    }
}
