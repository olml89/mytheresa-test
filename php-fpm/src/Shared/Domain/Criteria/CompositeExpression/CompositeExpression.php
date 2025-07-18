<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression;

use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;

abstract readonly class CompositeExpression implements Expression
{
    public function __construct(
        public Type $type,
    ) {
    }
}
