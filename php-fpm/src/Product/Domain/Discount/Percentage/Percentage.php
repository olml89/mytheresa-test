<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount\Percentage;

use Stringable;

final readonly class Percentage implements Stringable
{
    /**
     * @throws InvalidPercentageException
     */
    public function __construct(
        public int $value,
    ) {
        if ($this->value < 0) {
            throw InvalidPercentageException::negative($this->value);
        }

        if ($this->value > 100) {
            throw InvalidPercentageException::tooBig($this->value);
        }
    }

    public function greaterThan(Percentage $percentage): bool
    {
        return $this->value > $percentage->value;
    }

    public function calculate(int $value): int
    {
        return (int)($value * $this->value / 100);
    }

    public function __toString(): string
    {
        return $this->value . '%';
    }
}
