<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\PlanActionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_obligation_plan_action")
 * @ORM\Entity(repositoryClass=PlanActionRepository::class)
 */
class PlanAction
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
    private $action;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resultatAttendu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $porteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutAction;

    /**
     * @ORM\Column(type="date")
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=Preuve::class, inversedBy="actions")
     */
    private $preuve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getResultatAttendu(): ?string
    {
        return $this->resultatAttendu;
    }

    public function setResultatAttendu(?string $resultatAttendu): self
    {
        $this->resultatAttendu = $resultatAttendu;

        return $this;
    }

    public function getPorteur(): ?string
    {
        return $this->porteur;
    }

    public function setPorteur(?string $porteur): self
    {
        $this->porteur = $porteur;

        return $this;
    }

    public function getStatutAction(): ?string
    {
        return $this->statutAction;
    }

    public function setStatutAction(string $statutAction): self
    {
        $this->statutAction = $statutAction;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getPreuve(): ?Preuve
    {
        return $this->preuve;
    }

    public function setPreuve(?Preuve $preuve): self
    {
        $this->preuve = $preuve;

        return $this;
    }
}
