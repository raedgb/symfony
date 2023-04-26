<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory ;
use App\Entity\User;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(locale:'fr_FR') ;
        for ($i=0;$i<100;$i++){
            $user =new User();
            $user->setNom($faker->name);
            $user->setPrenom($faker->prenom);
            $user->setEmail($faker->email);
            $user->setPwd($faker->pwd);
            $user->setNumTel($faker->numTel);
            $user->setDateNaissc($faker->dateNaissc);
            $user->setAdresse($faker->adresse);
            $user->setSexe($faker->sexe);
            $user->setRole($faker->role);
            $user->setResettoken($faker->reset_token);
            $manager->persist($user);

        }
     
        $manager->flush();
    }
}
