<?php

namespace App\DataFixtures;

use App\Entity\Artwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ArtworkFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');
        $categories = CategoryFixtures::$categories;

        for ($i = 0; $i < 20; $i++) {
            $artwork = new Artwork();
            $artwork->setName(ucfirst($faker->word));
            $artwork->setDescription($faker->text);
            $artwork->setPicture($faker->image('public/img/artworks', 800, 450, null, false));

            $randomCategoryIndex = random_int(0, count($categories) - 1);
            $randomCategoryName = $categories[$randomCategoryIndex];
            $artwork->setCategory($this->getReference($randomCategoryName));

            $manager->persist($artwork);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
