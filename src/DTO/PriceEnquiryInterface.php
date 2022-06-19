<?php

namespace App\DTO;

interface PriceEnquiryInterface extends PromotionEnquiryInterface
{
    public function setPrice(int $price);

    public function setDiscountPrice(?int $discountPrice);

    public function getQuantity(): ?int;
}