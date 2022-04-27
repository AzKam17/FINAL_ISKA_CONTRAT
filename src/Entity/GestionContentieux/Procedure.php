<?php

namespace App\Entity\GestionContentieux;

use App\Entity\UserJuridique;
use App\Repository\GestionContentieux\ProcedureRepository;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Table (name="t_contentieux_procedure")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=ProcedureRepository::class)
 */
class Procedure
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Litige::class, inversedBy="procedures")
     */
    private $litige;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=UserJuridique::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLitige(): ?Litige
    {
        return $this->litige;
    }

    public function setLitige(?Litige $litige): self
    {
        $this->litige = $litige;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = CarbonImmutable::now();

        return $this;
    }

    public function getCreatedBy(): ?UserJuridique
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserJuridique $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
