<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionFilterInterface
{
    public function apply(PromotionEnquiryInterface $enquiry, Promotion ...$promotion): PromotionEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $quantity= $enquiry->getQuantity();
        $lowestPrice = $quantity * $price;

//        $modifyPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);



        $enquiry
            ->setDiscountPrice(250)
            ->setPrice(100)
            ->setPromotionId(3)
            ->setPromotionName('Black Friday half price sale');

        return $enquiry;
    }
}