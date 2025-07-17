<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use olml89\MyTheresaTest\Product\Domain\Discount\DiscountResolver;

final readonly class Product
{
    public function __construct(
        public Sku $sku,
        public string $name,
        public Category $category,
        private Price $price,
    ) {
    }

    public function price(DiscountResolver $discountResolver): Price
    {
        return $this->price->applyDiscount($discountResolver->resolveHighestDiscount($this));
    }
}
