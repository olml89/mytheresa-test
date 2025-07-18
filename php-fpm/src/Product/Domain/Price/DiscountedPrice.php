<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;

final readonly class DiscountedPrice implements Price
{
    use IsPrice;

    public function __construct(
        private OriginalPrice $original,
        private ?Discount $discount,
    ) {
        $this->validatePrice($this->original());
    }

    public function original(): int
    {
        return $this->original->value();
    }

    public function currency(): Currency
    {
        return $this->original->currency();
    }

    public function percentageDiscount(): ?Percentage
    {
        return $this->discount?->percentage();
    }

    /**
     * Conceptually, when we apply a 30% discount to a 100 EUR price, we are left with:
     * 30 EUR, the actual discount
     * 70 EUR, the final price.
     *
     * So in ubiquitous language, discount is the term applied to the reduced part stripped off the final price.
     */
    public function value(): int
    {
        if (is_null($this->discount)) {
            return $this->original->value();
        }

        $discountedPrice = $this->discount->price($this->original);

        return $this->original->subtract($discountedPrice)->value();
    }
}
