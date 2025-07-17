<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Product;

interface ProductSpecification
{
    public function isSatisfiedBy(Product $product): bool;
}
