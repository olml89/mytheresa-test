<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function list(int $limit, ?ProductSpecification $specification): array;
}
