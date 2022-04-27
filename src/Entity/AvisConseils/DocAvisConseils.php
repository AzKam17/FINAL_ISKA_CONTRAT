<?php

namespace App\Entity\AvisConseils;

use App\Entity\Abstracts\Document;
use App\Repository\AvisConseils\DocAvisConseilsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocAvisConseilsRepository::class)
 */
class DocAvisConseils extends Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Avis::class, inversedBy="docAvisConseils")
     * @ORM\JoinColumn(nullable=true)
     */
    private $avis;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvis(): ?Avis
    {
        return $this->avis;
    }

    public function setAvis(?Avis $avis): self
    {
        $this->avis = $avis;

        return $this;
    }
}
