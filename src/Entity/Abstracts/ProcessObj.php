<?php

namespace App\Entity\Abstracts;

use App\Entity\Departement;
use App\Entity\User;
use App\Entity\UserJuridique;
use App\Repository\Abstracts\ProcessObjRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;

/**
 * @ORM\Table (name="t_processes")
 * @ORM\HasLifecycleCallbacks
 * @InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *     "avis_conseils" = "App\Entity\AvisConseils\Avis"
 * })
 * @ORM\Entity(repositoryClass=ProcessObjRepository::class)
 */
abstract class ProcessObj
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
    private $currentState;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $actionManagerAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $attributionJuridiqueAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $finalJuridiqueAt;

    /**
     * @ORM\ManyToOne(targetEntity=UserJuridique::class)
     */
    private $userManagerJuridique;

    /**
     * @ORM\ManyToOne(targetEntity=UserJuridique::class)
     */
    private $userAgentJuridique;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $departementInitiateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function setCurrentState(string $currentState): self
    {
        $this->currentState = $currentState;

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
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     * @return $this
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = Carbon::now();

        return $this;
    }

    public function getActionManagerAt(): ?\DateTimeImmutable
    {
        return $this->actionManagerAt;
    }

    public function setActionManagerAt(?\DateTimeImmutable $actionManagerAt): self
    {
        $this->actionManagerAt = $actionManagerAt;

        return $this;
    }

    public function getAttributionJuridiqueAt(): ?\DateTimeImmutable
    {
        return $this->attributionJuridiqueAt;
    }

    public function setAttributionJuridiqueAt(?\DateTimeImmutable $attributionJuridiqueAt): self
    {
        $this->attributionJuridiqueAt = $attributionJuridiqueAt;

        return $this;
    }

    public function getFinalJuridiqueAt(): ?\DateTimeImmutable
    {
        return $this->finalJuridiqueAt;
    }

    public function setFinalJuridiqueAt(?\DateTimeImmutable $finalJuridiqueAt): self
    {
        $this->finalJuridiqueAt = $finalJuridiqueAt;

        return $this;
    }

    public function getUserManagerJuridique(): ?UserJuridique
    {
        return $this->userManagerJuridique;
    }

    public function setUserManagerJuridique(?UserJuridique $userManagerJuridique): self
    {
        $this->userManagerJuridique = $userManagerJuridique;

        return $this;
    }

    public function getUserAgentJuridique(): ?UserJuridique
    {
        return $this->userAgentJuridique;
    }

    public function setUserAgentJuridique(?UserJuridique $userAgentJuridique): self
    {
        $this->userAgentJuridique = $userAgentJuridique;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getDepartementInitiateur(): ?Departement
    {
        return $this->departementInitiateur;
    }

    public function setDepartementInitiateur(?Departement $departementInitiateur): self
    {
        $this->departementInitiateur = $departementInitiateur;

        return $this;
    }
}
