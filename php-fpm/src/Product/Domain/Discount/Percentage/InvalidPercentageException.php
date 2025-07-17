<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount\Percentage;

use RuntimeException;

final class InvalidPercentageException extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function negative(int $value): self
    {
        return new self(sprintf('A percentage cannot be negative, %s given', $value));
    }

    public static function tooBig(int $value): self
    {
        return new self(sprintf('A percentage cannot be greater than 100, %s given', $value));
    }
}
