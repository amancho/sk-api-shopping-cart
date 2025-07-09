<?php declare(strict_types=1);

namespace App\Infrastructure\Order\Persistence\Doctrine\Entity;

use App\Infrastructure\Shared\Persistence\Doctrine\Entity\AbstractTimestampedEntity;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "order_products")]
class DoctrineOrderProduct extends AbstractTimestampedEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $productId;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $size = null;

    #[ORM\Column(type: 'integer')]
    private int $price;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: DoctrineOrder::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private DoctrineOrder $order;

    public function setId(int $id): int
    {
        return $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOrder(): DoctrineOrder
    {
        return $this->order;
    }

    public function setOrder(DoctrineOrder $order): self
    {
        $this->order = $order;
        return $this;
    }

    public static function fromArray(array $data): self
    {
        $orderProduct = new self();

        $orderProduct->productId = $data['product_id'];
        $orderProduct->price = $data['price'];
        $orderProduct->quantity = $data['quantity'];

        $orderProduct->name = $data['name'] ?? null;
        $orderProduct->color = $data['color'] ?? null;
        $orderProduct->size = $data['size'] ?? null;
        $orderProduct->created_at = $data['created_at'] ?? new DateTimeImmutable();
        $orderProduct->updated_at = $data['updated_at'] ?? new DateTime();

        return $orderProduct;
    }
}
