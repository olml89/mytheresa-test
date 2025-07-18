<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria;

use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\AndExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\CompositeExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\NotExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\OrExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\Type;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\EqualTo;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Filter;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\GreaterThan;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\GreaterThanOrEqualTo;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\In;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\LessThan;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\LessThanOrEqualTo;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Like;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\NotEqualTo;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\NotIn;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Operator;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Order\Order;

final class Criteria
{
    public function __construct(
        public Expression $expression,
        public ?Order $order = null,
        public ?int $offset = null,
        public ?int $limit = null,
    ) {
    }

    public static function buildCompositeExpression(Type $type, Expression ...$clauses): CompositeExpression
    {
        return match ($type) {
            Type::NOT => new NotExpression(...$clauses),
            Type::AND => new AndExpression(...$clauses),
            Type::OR => new OrExpression(...$clauses),
        };
    }

    public static function buildFilter(Operator $operator, string $field, mixed $value): Filter
    {
        return match($operator) {
            Operator::EQ => new EqualTo($field, $value),
            Operator::NEQ => new NotEqualTo($field, $value),
            Operator::LIKE => new Like($field, $value),
            Operator::GT => new GreaterThan($field, $value),
            Operator::GTE => new GreaterThanOrEqualTo($field, $value),
            Operator::LT => new LessThan($field, $value),
            Operator::LTE => new LessThanOrEqualTo($field, $value),
            Operator::IN => new In($field, $value),
            Operator::NIN => new NotIn($field, $value),
        };
    }
}
