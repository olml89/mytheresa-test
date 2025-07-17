<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use olml89\MyTheresaTest\Product\Domain\Discount\Discount;

final class Product
{
    /** @var Collection<int, Discount> */
    private Collection $discounts;

    public function __construct(
        private readonly Sku $sku,
        private string $name,
        private Category $category,
        private Price $price,
    ) {
        $this->discounts = new ArrayCollection();
    }

    public function sku(): Sku
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function price(): Price
    {
        $highestDiscount = $this->discounts->reduce(
            function (?Discount $item, Discount $carry): Discount {
                return is_null($item) || $carry->greaterThan($item) ? $carry : $item;
            },
        );

        return $this->price->apply($highestDiscount);
    }

    public function setPrice(Price $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function addDiscount(Discount $discount): self
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts->add($discount);
            $discount->setProduct($this);
        }

        return $this;
    }
}
