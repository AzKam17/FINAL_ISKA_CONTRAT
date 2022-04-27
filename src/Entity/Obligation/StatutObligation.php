<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\StatutObligationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation_statut")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=StatutObligationRepository::class)
 */
class StatutObligation
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
     * @ORM\OneToMany(targetEntity=Obligation::class, mappedBy="statut")
     */
    private $obligations;

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
            $obligation->setStatut($this);
        }

        return $this;
    }

    public function removeObligation(Obligation $obligation): self
    {
        if ($this->obligations->removeElement($obligation)) {
            // set the owning side to null (unless already changed)
            if ($obligation->getStatut() === $this) {
                $obligation->setStatut(null);
            }
        }

        return $this;
    }
}
