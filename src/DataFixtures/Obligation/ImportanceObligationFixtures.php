<?php

namespace App\DataFixtures\Obligation;

use App\Entity\Obligation\ImportanceObligation;
use App\Entity\Obligation\Obligation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImportanceObligationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $obl = [
            'Haute', 'Moyenne', 'Basse'
        ];

        foreach ($obl as $o){
            $obli = (new ImportanceObligation())
                ->setLib($o)
                ->setDispoDeroulante(true)
            ;

            $manager->persist($obli);
            $manager->flush();
        }

        $manager->flush();
    }
}
