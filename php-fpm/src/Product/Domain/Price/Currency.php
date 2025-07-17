<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';

    public function equals(Currency $currency): bool
    {
        return $this->value === $currency->value;
    }
}
