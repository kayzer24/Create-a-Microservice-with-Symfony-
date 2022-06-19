<?php

namespace App\Entity;

use App\Repository\ProductPromotionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPromotionRepository::class)]
class ProductPromotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPromotions')]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Promotion::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Promotion $promotion;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private DateTimeInterface $validateTo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getValidateTo(): ?DateTimeInterface
    {
        return $this->validateTo;
    }

    public function setValidateTo(?DateTimeInterface $validateTo): self
    {
        $this->validateTo = $validateTo;

        return $this;
    }
}
