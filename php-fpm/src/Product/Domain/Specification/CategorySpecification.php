<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Criteria;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Operator;

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

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: Criteria::buildFilter(
                operator: Operator::EQ,
                field: 'category',
                value: $this->category,
            ),
        );
    }
}
