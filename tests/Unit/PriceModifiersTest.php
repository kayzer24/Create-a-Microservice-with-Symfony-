<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\EvenItemsMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;

class PriceModifiersTest extends ServiceTestCase
{
    public function testDateRangeMultiplierReturnsACorrectlyModifierPrice(): void
    {
        //Give
        $enquiry = (new LowestPriceEnquiry())
            ->setQuantity(5)
            ->setRequestDate('2022-11-27');

        $promotion = new Promotion();
        $promotion
            ->setName('Black Friday half price sale')
            ->setAdjustment(0.5)
            ->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"])
            ->setType('date_range_multiplier');

        $dateRangeModifier = new DateRangeMultiplier();
        // When
        $modifyPrice = $dateRangeModifier->modify(100, 5, $promotion, $enquiry);
        // Then
        $this->assertEquals(250, $modifyPrice);
    }

    public function testVoucherReturnsACorrectlyModifierPrice(): void
    {
        //Give
        $enquiry = (new LowestPriceEnquiry())
            ->setQuantity(5)
            ->setVoucherCode('OU812');

        $promotion = new Promotion();
        $promotion
            ->setName('Voucher OU812')
            ->setAdjustment(100)
            ->setCriteria(["code" => "OU812"])
            ->setType('fixed_price_voucher');

        $fixedPriceVoucher = new FixedPriceVoucher();
        // When
        $modifyPrice = $fixedPriceVoucher->modify(150, 5, $promotion, $enquiry);

        // Then
        $this->assertEquals(500, $modifyPrice);
    }

    public function testItemsMultiplierReturnsACorrectlyModifierPrice(): void
    {
        //Give
        $promotion = new Promotion();
        $promotion
            ->setName('Buy one get one free')
            ->setAdjustment(0.5)
            ->setCriteria(["minimum_quantity" => 2])
            ->setType('even_items_multiplier');

        $enquiry = (new LowestPriceEnquiry())
            ->setQuantity(5);

        $evenItemsMultiplier = new EvenItemsMultiplier();

        // When
        $modifyPrice = $evenItemsMultiplier->modify(150, 5, $promotion, $enquiry);

        // Then
        // ((100 * 4) * 0.5) + (1 * 100)
        $this->assertEquals(450, $modifyPrice);
    }

    public function testItemsMultiplierCorrectlyCalculatesAlternatives(): void
    {
        //Give
        $promotion = new Promotion();
        $promotion
            ->setName('Buy one get one half')
            ->setAdjustment(0.75)
            ->setCriteria(["minimum_quantity" => 2])
            ->setType('even_items_multiplier');

        $enquiry = (new LowestPriceEnquiry())
            ->setQuantity(5);

        $evenItemsMultiplier = new EvenItemsMultiplier();

        // When
        $modifyPrice = $evenItemsMultiplier->modify(150, 5, $promotion, $enquiry);

        // Then
        // ((100 * 4) * 0.5) + (1 * 100)
        $this->assertEquals(600, $modifyPrice);
    }
}
