<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression;

enum Type: string
{
    case AND = 'and';
    case OR = 'or';
    case NOT = 'not';
}
