<?php

namespace App\Entity\BaseDoc;

use App\Entity\Abstracts\Document;
use App\Repository\BaseDoc\ModeleContratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="t_bdoc_modele_contrat")
 * @ORM\Entity(repositoryClass=ModeleContratRepository::class)
 */
class ModeleContrat extends Document
{
}
