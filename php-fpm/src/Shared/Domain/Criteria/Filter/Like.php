<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\Filter;

final readonly class Like extends Filter
{
    public function __construct(string $field, mixed $value)
    {
        parent::__construct(
            field: $field,
            operator: Operator::LIKE,
            value: $value
        );
    }
}
