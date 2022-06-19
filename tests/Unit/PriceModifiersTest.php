<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;
use PHPUnit\Framework\TestCase;

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

    public function testVoucherReturnsACorrectlyModifierPrice()
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
}
