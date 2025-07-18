<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\Filter;

final readonly class NotEqualTo extends Filter
{
    public function __construct(string $field, mixed $value)
    {
        parent::__construct(
            field: $field,
            operator: Operator::NEQ,
            value: $value
        );
    }
}
