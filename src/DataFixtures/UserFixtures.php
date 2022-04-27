<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserJuridique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private $faker;
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(UserPasswordHasherInterface  $hasher)
    {
        $this->faker = Factory::create();
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager)
    {
        $departements = [];
        for($i = 0; $i<6; $i++){
            $departements[] = $this->getReference('departement-fixtures-'.$i);
        }

        $j = 0;
        foreach ($departements as $departement){
            for($i = 0; $i < 10; $i++){
                $u = $this->faker->firstName;
                $l = $this->faker->lastName;
                $user = (new User())
                    ->setFirstName($u)
                    ->setLastName($l)
                    ->setDepartement($departement)
                    ->setEmail($this->faker->email);

                $roles = [];
                if($i == 4){
                    if($departement->getSlug() === 'direction_juridique'){
                        $roles[] = 'ROLE_USER_BOSS_JURIDIQUE';
                        $userJuridique = (new UserJuridique())
                            ->setUser($user)
                        ;
                        $manager->persist($userJuridique);
                    }else{
                        $roles[] = 'ROLE_USER_MANAGER';
                    }
                }else if($departement->getSlug() === 'direction_juridique'){
                    $roles[] = 'ROLE_JURIDIQUE';
                    $userJuridique = (new UserJuridique())
                        ->setUser($user)
                    ;
                    $manager->persist($userJuridique);
                }

                $user->setRoles($roles);

                $user->setPassword(
                    $this->hasher->hashPassword(
                        $user, 'azerty'
                    )
                );

                $manager->persist($user);
                $manager->flush();
                $this->setReference('user-fixtures-'.$i.$j, $user);
            }
            $j++;
        }

    }

    public function getDependencies(): array
    {
        return [
            DepartementFixtures::class
        ];
    }
}
