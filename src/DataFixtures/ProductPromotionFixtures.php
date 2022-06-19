<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductPromotion;
use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductPromotionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $product = $manager->getRepository(Product::class)->find(1);
        $promotions = $manager->getRepository(Promotion::class)->findAll();

        foreach ($promotions as $i => $promotion) {
            $productPromo = (new ProductPromotion())
                ->setProduct($product)
                ->setPromotion($promotion);

            if ($i == 0) {
                $productPromo->setValidateTo(new \DateTime("2022-11-28"));
            }

            $manager->persist($productPromo);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            PromotionFixtures::class,
        ];
    }
}
