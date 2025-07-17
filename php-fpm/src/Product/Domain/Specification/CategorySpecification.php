<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Product;

final readonly class CategorySpecification implements ProductSpecification
{
    public function __construct(
        private Category $category,
    ) {
    }

    public function isSatisfiedBy(Product $product): bool
    {
        return $this->category === $product->category();
    }
}
