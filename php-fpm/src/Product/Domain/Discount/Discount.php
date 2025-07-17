<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount;

use olml89\MyTheresaTest\Product\Domain\Price;

interface Discount
{
    /**
     * Conceptually, if we apply a 30% discount to a 100€ item, the discount will be 30€ and 70€ will be the final price
     */
    public function apply(Price $price): int;
}
