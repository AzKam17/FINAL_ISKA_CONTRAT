<?php

namespace App\DataFixtures\Obligation;

use App\Entity\Obligation\SourceListe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SourceListeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $obl = [
            'Cahier de charges',
            'Ordonnace',
            'Décision',
            'Décret',
            'Loi',
        ];

        foreach ($obl as $o){
            $obli = (new SourceListe())
                ->setLib($o)
                ->setDispoDeroulante(true)
            ;

            $manager->persist($obli);
            $manager->flush();
        }

    }
}
