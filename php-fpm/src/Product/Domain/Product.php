<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

final class Product
{
    public function __construct(
        public readonly string $sku,
        public string $name,
        public Category $category,
        public int $price,
    ) {
    }
}
