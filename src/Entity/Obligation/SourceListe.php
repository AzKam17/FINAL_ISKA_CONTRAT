<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\SourceListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation_source_liste")
 * @ORM\Entity(repositoryClass=SourceListeRepository::class)
 */
class SourceListe
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
     * @ORM\Column(type="boolean")
     */
    private $dispoDeroulante;

    /**
     * @ORM\OneToMany(targetEntity=Obligation::class, mappedBy="sourceList")
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

    public function getDispoDeroulante(): ?bool
    {
        return $this->dispoDeroulante;
    }

    public function setDispoDeroulante(bool $dispoDeroulante): self
    {
        $this->dispoDeroulante = $dispoDeroulante;

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
            $obligation->setSourceList($this);
        }

        return $this;
    }

    public function removeObligation(Obligation $obligation): self
    {
        if ($this->obligations->removeElement($obligation)) {
            // set the owning side to null (unless already changed)
            if ($obligation->getSourceList() === $this) {
                $obligation->setSourceList(null);
            }
        }

        return $this;
    }
}
