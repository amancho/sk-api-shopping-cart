<?php declare(strict_types=1);

namespace App\Infrastructure\Cart\Persistence\Doctrine\Entity;

use App\Infrastructure\Shared\Persistence\Doctrine\Entity\AbstractTimestampedEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_items', indexes: [new ORM\Index(name: 'public_id_idx', columns: ['publicId'])])]
#[ORM\HasLifecycleCallbacks]
class DoctrineCartItem extends AbstractTimestampedEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'public_id', type: 'guid', unique: true)]
    private string $publicId;

    #[ORM\ManyToOne(targetEntity: DoctrineCart::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private DoctrineCart $cart;

    #[ORM\Column(name: 'product_id', type: 'integer', nullable: true)]
    private int $productId;

    #[ORM\Column(name: 'price', type: 'integer')]
    private int $price;

    #[ORM\Column(name: 'quantity', type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $size = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getCart(): DoctrineCart
    {
        return $this->cart;
    }

    public function setCart(DoctrineCart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;
        return $this;
    }
}
