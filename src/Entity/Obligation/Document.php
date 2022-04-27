<?php

namespace App\Entity\Obligation;

use App\Repository\Obligation\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document extends \App\Entity\Abstracts\Document
{
}
