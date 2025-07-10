<?php declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Domain\Cart\ValueObject\CartPublicId;
use App\Domain\Cart\ValueObject\CartShippingEmail;
use App\Domain\Cart\ValueObject\CartShippingPhone;
use App\Domain\Shared\Exception\InvalidUuid;

readonly class UpdateCartCommand
{
    public function __construct(
        private string             $name,
        private string             $address,
        private string             $city,
        private CartPublicId       $publicId,
        private CartShippingEmail  $email,
        private ?CartShippingPhone $phone = null,
        private ?string            $country = null,
        private ?string            $postal_code = null,
        private ?string            $province = null,
    ) {}

    /**
     * @throws InvalidUuid
     */
    public static function fromPrimitives(
        string $publicId,
        string $name,
        string $address,
        string $city,
        string $email,
        ?string$phone = null,
        ?string $country = null,
        ?string $postal_code = null,
        ?string $province = null,
    ): self {
        return new self(
            name:  $name,
            address: $address,
            city: $city,
            publicId: CartPublicId::fromUuid($publicId),
            email: CartShippingEmail::fromString($email),
            phone: $phone !== null ? CartShippingPhone::fromString($phone) : null,
            country: $country,
            postal_code: $postal_code,
            province: $province
        );
    }

    public function publicId(): CartPublicId {
        return $this->publicId;
    }

    public function name(): string {
        return $this->name;
    }

    public function address(): string {
        return $this->address;
    }

    public function city(): string {
        return $this->city;
    }

    public function email(): CartShippingEmail {
        return $this->email;
    }

    public function country(): ?string {
        return $this->country;
    }

    public function postalCode(): ?string {
        return $this->postal_code;
    }

    public function province(): ?string {
        return $this->province;
    }

    public function phone(): ?CartShippingPhone {
        return $this->phone;
    }
}
