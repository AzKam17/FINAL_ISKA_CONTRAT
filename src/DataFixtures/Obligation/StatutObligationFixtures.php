<?php

namespace App\DataFixtures\Obligation;

use App\Entity\Obligation\StatutObligation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatutObligationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $obl = [
            "OK",
            "NOK",
            "NA",
            "En cours",
        ];

        foreach ($obl as $o){
            $obli = (new StatutObligation())
                ->setLib($o)
            ;

            $manager->persist($obli);
            $manager->flush();
        }
    }
}
