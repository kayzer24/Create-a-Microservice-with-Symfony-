<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase
{
    public function testLowestPricePromotionsFilteringIsAppliedCorrectly(): void
    {
        $product =  new Product();
        $product->setPrice(100);

        //Given
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setProduct($product);
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate("2022-11-27");
        $enquiry->setVoucherCode('OU812');

        $promotions = $this->promotionsDataProvider();

        $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);

        //When
        $FilteredEnquiry = $lowestPriceFilter->apply($enquiry, ...$promotions);

        //Then
        $this->assertSame(100, $FilteredEnquiry->getPrice());
        $this->assertSame(250, $FilteredEnquiry->getDiscountPrice());
        $this->assertSame('Black Friday half price sale', $FilteredEnquiry->getPromotionName());
    }

    private function promotionsDataProvider(): array
    {
        $promotionOne = new Promotion();
        $promotionOne
            ->setName('Black Friday half price sale')
            ->setAdjustment(0.5)
            ->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"])
            ->setType('date_range_multiplier');

        $promotionTwo = new Promotion();
        $promotionTwo
            ->setName('Voucher OU812')
            ->setAdjustment(100)
            ->setCriteria(["code" => "OU812"])
            ->setType('fixed_price_voucher');

        $promotionThree = new Promotion();
        $promotionThree
            ->setName('Buy one get one free')
            ->setAdjustment(0.5)
            ->setCriteria(["minimum_quantity" => 2])
            ->setType('even_items_multiplier');

        return [$promotionOne, $promotionTwo, $promotionThree];
    }
}
