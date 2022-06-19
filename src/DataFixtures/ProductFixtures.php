<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 2; $i++) {
            $product = (new Product())
                ->setPrice(100 * $i);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
