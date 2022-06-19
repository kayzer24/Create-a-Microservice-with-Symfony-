<?php

namespace App\Filter;

use App\DTO\PriceEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Modifier\Factory\PriceModifierFactoryInterface;

class LowestPriceFilter implements PriceFilterInterface
{
    public function __construct(private PriceModifierFactoryInterface $priceModifierFactory)
    {
    }

    public function apply(PriceEnquiryInterface $enquiry, Promotion ...$promotions): PriceEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $enquiry->setPrice($price);
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $quantity * $price;

        foreach ($promotions as $promotion) {
            $priceModifier = $this->priceModifierFactory->create($promotion->getType());

            $modifyPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);

            if ($modifyPrice < $lowestPrice) {
                $enquiry
                    ->setDiscountPrice($modifyPrice)
                    ->setPromotionId($promotion->getId())
                    ->setPromotionName($promotion->getName());

                // 2. Update $lowestPrice
                $lowestPrice = $modifyPrice;
            }
        }

        return $enquiry;
    }
}