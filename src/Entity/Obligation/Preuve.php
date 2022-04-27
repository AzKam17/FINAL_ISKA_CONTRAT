<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\PreuveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation_preuve")
 * @ORM\Entity(repositoryClass=PreuveRepository::class)
 */
class Preuve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Obligation::class, inversedBy="preuves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $obligation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitule;


    /**
     * @ORM\OneToOne(targetEntity=Document::class, cascade={"persist", "remove"})
     */
    private $pieceJustificatif;

    /**
     * @ORM\OneToMany(targetEntity=PlanAction::class, mappedBy="preuve", cascade={"persist"})
     */
    private $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObligation(): ?Obligation
    {
        return $this->obligation;
    }

    public function setObligation(?Obligation $obligation): self
    {
        $this->obligation = $obligation;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getPieceJustificatif(): ?Document
    {
        return $this->pieceJustificatif;
    }

    public function setPieceJustificatif(?Document $pieceJustificatif): self
    {
        $this->pieceJustificatif = $pieceJustificatif;

        return $this;
    }

    /**
     * @return Collection<int, PlanAction>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(PlanAction $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setPreuve($this);
        }

        return $this;
    }

    public function removeAction(PlanAction $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getPreuve() === $this) {
                $action->setPreuve(null);
            }
        }

        return $this;
    }
}
