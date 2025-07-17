<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Helpers;

use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\ProductRepository;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;

final readonly class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var Product[]
     */
    private array $products;

    public function __construct(Product ...$products)
    {
        $this->products = $products;
    }

    public function list(int $limit, ?ProductSpecification $specification): array
    {
        return array_slice(
            array_filter(
                $this->products,
                // If there's no specification apply a true, pass the filter
                fn (Product $product): bool => $specification?->isSatisfiedBy($product) ?? true,
            ),
            offset: 0,
            length: $limit,
        );
    }
}
