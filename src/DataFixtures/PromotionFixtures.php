<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $promotion1 = (new Promotion())
        ->setName('Black Friday half price sale')
        ->setType('date_range_multiplier')
        ->setAdjustment(0.5)
        ->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"]);

        $manager->persist($promotion1);

        $promotion2 = (new Promotion())
            ->setName('Voucher OU812')
            ->setType('fixed_price_voucher')
            ->setAdjustment(100)
            ->setCriteria(["code" => "OU812"]);

        $manager->persist($promotion2);

        $promotion3 = (new Promotion())
            ->setName('Buy one get one free')
            ->setType('even_items_multiplier')
            ->setAdjustment(0.5)
            ->setCriteria(["minimum_quantity" => 2]);

        $manager->persist($promotion3);

        $manager->flush();
    }
}
