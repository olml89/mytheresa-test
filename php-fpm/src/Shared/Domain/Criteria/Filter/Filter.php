<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\Filter;

use InvalidArgumentException;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;

abstract readonly class Filter implements Expression
{
    public function __construct(
        public string $field,
        public Operator $operator,
        public mixed $value,
    ) {
        if (is_array($value) && !$this->operator->comparesMultipleValues()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Operator %s cannot compare multiple values.',
                    $this->operator->value,
                )
            );
        }
    }
}
