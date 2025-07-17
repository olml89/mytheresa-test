<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Discount;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price;

final readonly class DumbDiscount implements Discount
{
    public function __construct(
        private Percentage $percentage,
    ) {
    }

    public function apply(Price $price): int
    {
        return $this->percentage->calculate($price->original);
    }
}
