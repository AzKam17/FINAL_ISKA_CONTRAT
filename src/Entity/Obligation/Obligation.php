<?php

namespace App\Entity\Obligation;

use App\Entity\User;
use App\Entity\Obligation\Document;
use App\Entity\UserJuridique;
use App\Repository\Obligation\ObligationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation")
 * @ORM\Entity(repositoryClass=ObligationRepository::class)
 */
class Obligation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $sourceDisposition;

    /**
     * @ORM\Column(type="text")
     */
    private $reference;

    /**
     * @ORM\Column(type="text")
     */
    private $pointsAbordes;

    /**
     * @ORM\Column(type="text")
     */
    private $obligation;

    /**
     * @ORM\ManyToOne(targetEntity=StatutObligation::class, inversedBy="obligations")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=ImportanceObligation::class, inversedBy="obligations")
     */
    private $importanceObligation;

    /**
     * @ORM\ManyToOne(targetEntity=SourceListe::class, inversedBy="obligations")
     */
    private $sourceList;

    /**
     * @ORM\ManyToOne(targetEntity=ReferenceListe::class, inversedBy="obligations", cascade={"persist"})
     */
    private $referenceListe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sanctions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prevues;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $impact;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, cascade={"persist", "remove"})
     */
    private $document;

    /**
     * @ORM\OneToMany(targetEntity=Preuve::class, mappedBy="obligation", cascade={"persist"})
     */
    private $preuves;

    /**
     * @ORM\ManyToOne(targetEntity=UserJuridique::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    public function __construct()
    {
        $this->preuves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceDisposition(): ?string
    {
        return $this->sourceDisposition;
    }

    public function setSourceDisposition(string $sourceDisposition): self
    {
        $this->sourceDisposition = $sourceDisposition;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPointsAbordes(): ?string
    {
        return $this->pointsAbordes;
    }

    public function setPointsAbordes(string $pointsAbordes): self
    {
        $this->pointsAbordes = $pointsAbordes;

        return $this;
    }

    public function getObligation(): ?string
    {
        return $this->obligation;
    }

    public function setObligation(string $obligation): self
    {
        $this->obligation = $obligation;

        return $this;
    }

    public function getStatut(): ?StatutObligation
    {
        return $this->statut;
    }

    public function setStatut(?StatutObligation $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getImportanceObligation(): ?ImportanceObligation
    {
        return $this->importanceObligation;
    }

    public function setImportanceObligation(?ImportanceObligation $importanceObligation): self
    {
        $this->importanceObligation = $importanceObligation;

        return $this;
    }

    public function getSourceList(): ?SourceListe
    {
        return $this->sourceList;
    }

    public function setSourceList(?SourceListe $sourceList): self
    {
        $this->sourceList = $sourceList;

        return $this;
    }

    public function getReferenceListe(): ?ReferenceListe
    {
        return $this->referenceListe;
    }

    public function setReferenceListe(?ReferenceListe $referenceListe): self
    {
        $this->referenceListe = $referenceListe;

        return $this;
    }

    public function getSanctions(): ?string
    {
        return $this->sanctions;
    }

    public function setSanctions(string $sanctions): self
    {
        $this->sanctions = $sanctions;

        return $this;
    }

    public function getPrevues(): ?string
    {
        return $this->prevues;
    }

    public function setPrevues(string $prevues): self
    {
        $this->prevues = $prevues;

        return $this;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(string $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return Collection<int, Preuve>
     */
    public function getPreuves(): Collection
    {
        return $this->preuves;
    }

    public function addPreuve(Preuve $preuve): self
    {
        if (!$this->preuves->contains($preuve)) {
            $this->preuves[] = $preuve;
            $preuve->setObligation($this);
        }

        return $this;
    }

    public function removePreuve(Preuve $preuve): self
    {
        if ($this->preuves->removeElement($preuve)) {
            // set the owning side to null (unless already changed)
            if ($preuve->getObligation() === $this) {
                $preuve->setObligation(null);
            }
        }

        return $this;
    }

    public function getResponsable(): ?UserJuridique
    {
        return $this->responsable;
    }

    public function setResponsable(?UserJuridique $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }
}
