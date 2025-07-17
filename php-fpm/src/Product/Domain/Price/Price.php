<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

interface Price
{
    public function value(): int;
    public function currency(): Currency;
    public function subtract(Price $price): Price;
}
