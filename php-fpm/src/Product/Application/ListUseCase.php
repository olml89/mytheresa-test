<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\ProductRepository;

final readonly class ListUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @return Product[]
     */
    public function list(Filter $filter): array
    {
        $filter->sanitize();

        return $this->productRepository->list($filter->limit, $filter->specification);
    }
}
