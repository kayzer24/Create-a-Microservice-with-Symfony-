<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplier implements PriceModifierInterface
{

    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($quantity < $promotion->getCriteria()['minimum_quantity']) {
            return $price * $quantity;
        }

        // (price * quantity) * promotion->getAdjustment
        $oddCount = $quantity % $promotion->getCriteria()['minimum_quantity'];

        $eventCount = $quantity - $oddCount;

        return (($price * $eventCount) * $promotion->getAdjustment()) + ($price * $oddCount);
    }
}