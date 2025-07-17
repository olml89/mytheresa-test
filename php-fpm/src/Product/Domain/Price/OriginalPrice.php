<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

final readonly class OriginalPrice implements Price
{
    use IsPrice;

    public function __construct(
        private int $original,
        private Currency $currency,
    ) {
        $this->validatePrice($this->original);
    }

    public function value(): int
    {
        return $this->original;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }
}
