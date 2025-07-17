<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount\Percentage;

final readonly class Percentage
{
    /**
     * @throws InvalidPercentageException
     */
    public function __construct(
        private int $value,
    ) {
        if ($this->value < 0) {
            throw InvalidPercentageException::negative($this->value);
        }

        if ($this->value > 100) {
            throw InvalidPercentageException::tooBig($this->value);
        }
    }

    public function calculate(int $value): int
    {
        return (int)($value * $this->value / 100);
    }
}
