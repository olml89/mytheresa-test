<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression;

use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;

final readonly class NotExpression extends CompositeExpression
{
    public function __construct(
        public Expression $clause,
    ) {
        parent::__construct(Type::NOT);
    }
}
