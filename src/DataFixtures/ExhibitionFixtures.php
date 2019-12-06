<?php

namespace App\DataFixtures;

use App\Entity\Exhibition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ExhibitionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $exhibition = new Exhibition();
            $exhibition->setName($faker->city);
            $exhibition->setPresentation($faker->text);

            if ($i < 5) {
                $completedDate = $faker->dateTimeBetween('-3 years', 'now');
                $exhibition->setDate($completedDate);
            } else {
                $upcomingDate = $faker->dateTimeBetween('now', '+2 years');
                $exhibition->setDate($upcomingDate);
            }

            $manager->persist($exhibition);
        }

        $manager->flush();
    }
}
