<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use Stringable;

final readonly class Sku implements Stringable
{
    public function __construct(
        private string $sku,
    ) {
        if (mb_strlen($this->sku) !== 6 || !is_numeric($this->sku)) {
            throw new InvalidSkuException($this->sku);
        }
    }

    public function __toString(): string
    {
        return $this->sku;
    }
}
