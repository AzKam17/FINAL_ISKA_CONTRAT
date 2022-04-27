<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProcessObjFiltersExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('processObjFilter', [$this, 'getValue']),
        ];
    }

    public function getValue($value): string
    {
        $vals = [
            'avis_title' => 'Avis & Conseils',
            'avis_new_title' => "Nouvelle demande d'avis & de conseils",
            'avis_new_block_title' => "Effectuer une demande d'avis / conseil"
        ];

        return $vals[$value];
    }
}
