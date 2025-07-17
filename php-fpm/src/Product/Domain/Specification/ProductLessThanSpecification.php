<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Product;

final readonly class ProductLessThanSpecification implements ProductSpecification
{
    public function __construct(
        private OriginalPrice $price,
    ) {
    }

    public function isSatisfiedBy(Product $product): bool
    {
        return $product->price()->value() < $this->price->value();
    }
}
