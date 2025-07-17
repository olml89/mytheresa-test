<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;

final readonly class Price
{
    public function __construct(
        public int $original,
        public Currency $currency,
        public ?Discount $discount = null,
    ) {
    }

    public function applyDiscount(?Discount $discount): self
    {
        return new self($this->original, $this->currency, $discount);
    }

    public function calculate(): int
    {
        if (is_null($this->discount)) {
            return $this->original;
        }

        return $this->original - $this->discount->apply($this);
    }
}
