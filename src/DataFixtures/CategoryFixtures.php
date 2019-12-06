<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $paintCategory = new Category();
        $paintCategory->setName("Peinture");
        $this->addReference("paintCategory", $paintCategory);
        $manager->persist($paintCategory);

        $drawingCategory = new Category();
        $drawingCategory->setName("Dessin");
        $this->addReference("drawingCategory", $drawingCategory);
        $manager->persist($drawingCategory);

        $sculptureCategory = new Category();
        $sculptureCategory->setName("Sculpture");
        $this->addReference("sculptureCategory", $sculptureCategory);
        $manager->persist($sculptureCategory);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
