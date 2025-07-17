<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;

final readonly class Price
{
    public function __construct(
        public int $original,
        public Currency $currency,
    ) {
        if ($this->original < 0) {
            throw new InvalidPriceException($this->original);
        }
    }

    /**
     * Conceptually, when we apply a 30% discount to a 100 EUR price, we are left with:
     * 30 EUR, the actual discount
     * 70 EUR, the final price.
     *
     * So in ubiquitous language, discount is the term applied to the reduced part stripped off the final price.
     */
    public function apply(?Discount $discount): self
    {
        if (is_null($discount)) {
            return $this;
        }

        return new self(
            original: $this->original - $discount->price($this)->original,
            currency: $this->currency,
        );
    }
}
