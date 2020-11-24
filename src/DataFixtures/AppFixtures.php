<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setName("Categorie $i");
            $manager->persist($category);
            $manager->flush();
        }
    }
}
