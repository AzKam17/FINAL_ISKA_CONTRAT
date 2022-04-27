<?php

namespace App\DataFixtures\Obligation;

use App\Entity\Obligation\ReferenceListe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReferenceListeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $obl = [
            'Article 1',
            'Article 2',
            'Article 3',
        ];

        foreach ($obl as $o){
            $obli = (new ReferenceListe())
                ->setLib($o)
                ->setDispoDeroulante(true)
            ;

            $manager->persist($obli);
            $manager->flush();
        }

        $manager->flush();
    }
}
