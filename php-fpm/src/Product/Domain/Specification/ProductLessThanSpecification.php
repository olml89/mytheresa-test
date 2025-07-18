<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Criteria;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Operator;

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

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: Criteria::buildFilter(
                operator: Operator::LT,
                field: 'price.original',
                value: $this->price->value(),
            ),
        );
    }
}
