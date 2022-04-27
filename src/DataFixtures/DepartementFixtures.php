<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $departements = [
            [
                'lib' => 'Financier',
                'color' => ''
            ],
            [
                'lib' => 'Informatique',
                'color' => ''
            ],
            [
                'lib' => 'Ressources Humaines',
                'color' => ''
            ],
            [
                'lib' => 'Logistique',
                'color' => ''
            ],
            [
                'lib' => 'Direction Generale',
                'color' => ''
            ],
            [
                'lib' => 'Direction Juridique',
                'color' => ''
            ],
        ];


        $i = 0;
        foreach ($departements as $dep){
            $newDepartement = (new Departement())
                ->setLib($dep['lib'])
                ->setColor('')
            ;

            $manager->persist($newDepartement);
            $this->setReference('departement-fixtures-'.$i, $newDepartement);
            $manager->flush();
            $i++;
        }
    }
}
