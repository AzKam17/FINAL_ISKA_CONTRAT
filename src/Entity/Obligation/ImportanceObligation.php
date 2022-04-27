<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\ImportanceObligationRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation_importance")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=ImportanceObligationRepository::class)
 */
class ImportanceObligation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lib;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity=Obligation::class, mappedBy="importanceObligation")
     */
    private $obligations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dispoDeroulante;

    public function __construct()
    {
        $this->obligations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLib(): ?string
    {
        return $this->lib;
    }

    public function setLib(string $lib): self
    {
        $this->lib = $lib;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setSlug(): self
    {
        $this->slug = (new Slugify())->slugify(
            $this->getLib(), '_'
        );

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @return $this
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = Carbon::now();

        return $this;
    }

    /**
     * @return Collection<int, Obligation>
     */
    public function getObligations(): Collection
    {
        return $this->obligations;
    }

    public function addObligation(Obligation $obligation): self
    {
        if (!$this->obligations->contains($obligation)) {
            $this->obligations[] = $obligation;
            $obligation->setImportanceObligation($this);
        }

        return $this;
    }

    public function removeObligation(Obligation $obligation): self
    {
        if ($this->obligations->removeElement($obligation)) {
            // set the owning side to null (unless already changed)
            if ($obligation->getImportanceObligation() === $this) {
                $obligation->setImportanceObligation(null);
            }
        }

        return $this;
    }

    public function getDispoDeroulante(): ?bool
    {
        return $this->dispoDeroulante;
    }

    public function setDispoDeroulante(bool $dispoDeroulante): self
    {
        $this->dispoDeroulante = $dispoDeroulante;

        return $this;
    }
}
