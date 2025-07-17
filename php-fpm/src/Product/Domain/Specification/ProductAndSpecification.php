<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Product;

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
}
