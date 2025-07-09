<?php declare(strict_types=1);

namespace App\Infrastructure\Shared\Persistence\Doctrine\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class AbstractTimestampedEntity
{
    #[ORM\Column(type: "datetime_immutable")]
    protected \DateTimeImmutable $created_at;

    #[ORM\Column(type: "datetime")]
    protected \DateTime $updated_at;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTime();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }
}
