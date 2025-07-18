<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\AndExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Criteria;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;

final readonly class ProductAndSpecification implements ProductSpecification
{
    /**
     * @var ProductSpecification[]
     */
    private array $specifications;

    public function __construct(ProductSpecification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(Product $product): bool
    {
        return array_all(
            $this->specifications,
            fn (ProductSpecification $specification): bool => $specification->isSatisfiedBy($product),
        );
    }

    public function criteria(): Criteria
    {
        return new Criteria(
            expression: new AndExpression(
                ...array_map(
                    fn (ProductSpecification $specification): Expression => $specification->criteria()->expression,
                    $this->specifications,
                ),
            ),
        );
    }
}
