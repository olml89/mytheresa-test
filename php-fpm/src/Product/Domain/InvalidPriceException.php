<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use RuntimeException;

final class InvalidPriceException extends RuntimeException
{
    public function __construct(int $value)
    {
        parent::__construct(
            sprintf('A price cannot be negative, %s given.', $value)
        );
    }
}
