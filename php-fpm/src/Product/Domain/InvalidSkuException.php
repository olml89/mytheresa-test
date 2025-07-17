<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

use RuntimeException;

final class InvalidSkuException extends RuntimeException
{
    public function __construct(string $value)
    {
        parent::__construct(
            sprintf('sku must have a length between of 6 numeric digits, \'%s\' given.', $value)
        );
    }
}
