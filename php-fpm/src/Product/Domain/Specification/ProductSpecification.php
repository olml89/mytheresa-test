<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Specification;

interface ProductSpecification extends Specification
{
    public function isSatisfiedBy(Product $product): bool;
}
