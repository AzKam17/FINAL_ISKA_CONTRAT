<?php

namespace App\Entity\GestionContentieux;

use App\Entity\UserJuridique;
use App\Repository\GestionContentieux\LitigeRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Table (name="t_contentieux_litige")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=LitigeRepository::class)
 */
class Litige
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $origineCas;

    /**
     * @ORM\Column(type="text")
     */
    private $fait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avisJuridique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estimationFinanciere;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $provision;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $juridiction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estimationConsequencesFinancieresProfit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $partieDemandeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $partieDefendeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avocat;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Procedure::class, mappedBy="litige", cascade={"persist"})
     */
    private $procedures;

    /**
     * @ORM\ManyToOne(targetEntity=UserJuridique::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isClosed;

    public function __construct()
    {
        $this->procedures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCas(): ?\DateTimeInterface
    {
        return $this->dateCas;
    }

    public function setDateCas(\DateTimeInterface $dateCas): self
    {
        $this->dateCas = $dateCas;

        return $this;
    }

    public function getOrigineCas(): ?string
    {
        return $this->origineCas;
    }

    public function setOrigineCas(string $origineCas): self
    {
        $this->origineCas = $origineCas;

        return $this;
    }

    public function getFait(): ?string
    {
        return $this->fait;
    }

    public function setFait(string $fait): self
    {
        $this->fait = $fait;

        return $this;
    }

    public function getAvisJuridique(): ?string
    {
        return $this->avisJuridique;
    }

    public function setAvisJuridique(string $avisJuridique): self
    {
        $this->avisJuridique = $avisJuridique;

        return $this;
    }

    public function getEstimationFinanciere(): ?string
    {
        return $this->estimationFinanciere;
    }

    public function setEstimationFinanciere(string $estimationFinanciere): self
    {
        $this->estimationFinanciere = $estimationFinanciere;

        return $this;
    }

    public function getProvision(): ?string
    {
        return $this->provision;
    }

    public function setProvision(string $provision): self
    {
        $this->provision = $provision;

        return $this;
    }

    public function getJuridiction(): ?string
    {
        return $this->juridiction;
    }

    public function setJuridiction(string $juridiction): self
    {
        $this->juridiction = $juridiction;

        return $this;
    }

    public function getEstimationConsequencesFinancieresProfit(): ?string
    {
        return $this->estimationConsequencesFinancieresProfit;
    }

    public function setEstimationConsequencesFinancieresProfit(string $estimationConsequencesFinancieresProfit): self
    {
        $this->estimationConsequencesFinancieresProfit = $estimationConsequencesFinancieresProfit;

        return $this;
    }

    public function getPartieDemandeur(): ?string
    {
        return $this->partieDemandeur;
    }

    public function setPartieDemandeur(string $partieDemandeur): self
    {
        $this->partieDemandeur = $partieDemandeur;

        return $this;
    }

    public function getPartieDefendeur(): ?string
    {
        return $this->partieDefendeur;
    }

    public function setPartieDefendeur(string $partieDefendeur): self
    {
        $this->partieDefendeur = $partieDefendeur;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getAvocat(): ?string
    {
        return $this->avocat;
    }

    public function setAvocat(string $avocat): self
    {
        $this->avocat = $avocat;

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
     * @return Collection<int, Procedure>
     */
    public function getProcedures(): Collection
    {
        return $this->procedures;
    }

    public function addProcedure(Procedure $procedure): self
    {
        if (!$this->procedures->contains($procedure)) {
            $this->procedures[] = $procedure;
            $procedure->setLitige($this);
        }

        return $this;
    }

    public function removeProcedure(Procedure $procedure): self
    {
        if ($this->procedures->removeElement($procedure)) {
            // set the owning side to null (unless already changed)
            if ($procedure->getLitige() === $this) {
                $procedure->setLitige(null);
            }
        }

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

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function prePersistClosed()
    {
        $this->setIsClosed(false);
    }
}
