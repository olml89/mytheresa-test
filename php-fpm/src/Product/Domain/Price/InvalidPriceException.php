<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Price;

use RuntimeException;

final class InvalidPriceException extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function negativePrice(int $value): self
    {
        return new self(sprintf('A price cannot be negative, %s given.', $value));
    }

    public static function differentCurrency(Currency $currency, Currency $anotherCurrency): self
    {
        return new self(
            sprintf(
                'Cannot operate with different currencies, %s  and %s given.',
                $currency->value,
                $anotherCurrency->value,
            ),
        );
    }
}
