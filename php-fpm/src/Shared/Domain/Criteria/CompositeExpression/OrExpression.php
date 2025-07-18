<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression;

use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;

final readonly class OrExpression extends CompositeExpression
{
    /**
     * @var array<array-key, Expression>
     */
    public array $clauses;

    public function __construct(Expression ...$clauses)
    {
        $this->clauses = $clauses;

        parent::__construct(Type::OR);
    }
}
