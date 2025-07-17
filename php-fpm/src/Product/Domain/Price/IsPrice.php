<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

/**
 * @mixin Price
 */
trait IsPrice
{
    private function validatePrice(int $value): void
    {
        if ($value < 0) {
            throw InvalidPriceException::negativePrice($value);
        }
    }

    public function subtract(Price $price): Price
    {
        if (!$this->currency()->equals($price->currency())) {
            throw InvalidPriceException::differentCurrency($this->currency(), $price->currency());
        }

        return new OriginalPrice(
            original: $this->value() - $price->value(),
            currency: $this->currency(),
        );
    }
}
