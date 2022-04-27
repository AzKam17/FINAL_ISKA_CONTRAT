<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class StatutContratExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('statutContrat', [$this, 'doSomething']),
        ];
    }

    public function doSomething($value)
    {
        $statut = [
            "en_attente_manager" => "En attente de validation par le chef de département",
            "demande_rejetee_manager" => "Demande rejétée par le manager",
            "demande_acceptee_manager" => "Demande acceptée par le manager",
            "demande_non_attribuee" => "Demande au niveau du service juridique en attente d'attribution",
            "demande_attribuee" => "Demande attribuée",
            "demande_validee" => "Demande validée",
            "demande_rejetee" => "Demande rejétée",
        ];

        return $statut[$value];
    }
}
