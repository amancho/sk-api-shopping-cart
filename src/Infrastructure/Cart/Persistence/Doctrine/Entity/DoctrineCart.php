<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Entity;

use App\Infrastructure\Shared\Persistence\Doctrine\Entity\AbstractTimestampedEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'carts', indexes: [new ORM\Index(name: 'public_id_idx', columns: ['publicId'])])]
#[ORM\HasLifecycleCallbacks]
class DoctrineCart extends AbstractTimestampedEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: "public_id", type: 'guid', unique: true)]
    private string $publicId;

    #[ORM\Column(type: 'string', length: 30)]
    private string $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $checkoutId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $userId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $orderId = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $sessionId = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private ?string $shippingName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $shippingAddress = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $shippingCity = null;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private ?string $shippingPostalCode = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $shippingProvince = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $shippingCountry = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $shippingPhone = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $shippingEmail = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $metadata = null;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function setPublicId(string $publicId): self
    {
        $this->publicId = $publicId;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCheckoutId(): string
    {
        return $this->checkoutId;
    }

    public function setCheckoutId(?string $checkoutId): self
    {
        $this->checkoutId = $checkoutId;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function getShippingName(): ?string
    {
        return $this->shippingName;
    }

    public function setShippingName(?string $shippingName): self
    {
        $this->shippingName = $shippingName;
        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getShippingCity(): ?string
    {
        return $this->shippingCity;
    }

    public function setShippingCity(?string $shippingCity): self
    {
        $this->shippingCity = $shippingCity;
        return $this;
    }

    public function getShippingPostalCode(): ?string
    {
        return $this->shippingPostalCode;
    }

    public function setShippingPostalCode(?string $shippingPostalCode): self
    {
        $this->shippingPostalCode = $shippingPostalCode;
        return $this;
    }

    public function getShippingProvince(): ?string
    {
        return $this->shippingProvince;
    }

    public function setShippingProvince(?string $shippingProvince): self
    {
        $this->shippingProvince = $shippingProvince;
        return $this;
    }

    public function getShippingCountry(): ?string
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry(?string $shippingCountry): self
    {
        $this->shippingCountry = $shippingCountry;
        return $this;
    }

    public function getShippingPhone(): ?string
    {
        return $this->shippingPhone;
    }

    public function setShippingPhone(?string $shippingPhone): self
    {
        $this->shippingPhone = $shippingPhone;
        return $this;
    }

    public function getShippingEmail(): ?string
    {
        return $this->shippingEmail;
    }

    public function setShippingEmail(?string $shippingEmail): self
    {
        $this->shippingEmail = $shippingEmail;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }
}
