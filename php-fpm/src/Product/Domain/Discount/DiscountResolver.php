<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount;

use olml89\MyTheresaTest\Product\Domain\Product;

interface DiscountResolver
{
    public function resolveHighestDiscount(Product $product): ?Discount;
}
