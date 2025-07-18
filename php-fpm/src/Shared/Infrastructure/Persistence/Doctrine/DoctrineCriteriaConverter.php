<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison as DoctrineComparison;
use Doctrine\Common\Collections\Expr\CompositeExpression as DoctrineCompositeExpression;
use Doctrine\Common\Collections\Expr\Expression as DoctrineExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\AndExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\CompositeExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\NotExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\OrExpression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\CompositeExpression\Type;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Criteria;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Expression;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Filter;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Filter\Operator;
use olml89\MyTheresaTest\Shared\Domain\Criteria\Order\Order;

final class DoctrineCriteriaConverter
{
    /**
     * @param array<string, string> $criteriaToDoctrineFields
     */
    public function convert(Criteria $criteria, array $criteriaToDoctrineFields = []): DoctrineCriteria
    {
        return new DoctrineCriteria(
            expression: $this->convertExpression($criteria->expression),
            orderings: $this->convertOrder($criteria->order, $criteriaToDoctrineFields),
            firstResult: $criteria->offset ?? 0,
            maxResults: $criteria->limit,
        );
    }

    private function convertExpression(Expression $expression): DoctrineExpression
    {
        if ($expression instanceof CompositeExpression) {
            return new DoctrineCompositeExpression(
                type: $this->convertCompositeExpressionType($expression),
                expressions: array_map(
                    fn (Expression $expression): DoctrineExpression => $this->convertExpression($expression),
                    $this->getCompositeExpressionClauses($expression),
                ),
            );
        }

        /** @var Filter $expression */
        return new DoctrineComparison(
            field: $expression->field,
            op: $this->convertExpressionOperator($expression->operator),
            value: $expression->value,
        );
    }

    private function convertExpressionOperator(Operator $operator): string
    {
        return match ($operator) {
            Operator::EQ => DoctrineComparison::EQ,
            Operator::NEQ => DoctrineComparison::NEQ,
            Operator::GT => DoctrineComparison::GT,
            Operator::GTE => DoctrineComparison::GTE,
            Operator::LT => DoctrineComparison::LT,
            Operator::LTE => DoctrineComparison::LTE,
            Operator::IN => DoctrineComparison::IN,
            Operator::NIN => DoctrineComparison::NIN,
            Operator::LIKE => DoctrineComparison::CONTAINS,
        };
    }

    private function convertCompositeExpressionType(CompositeExpression $expression): string
    {
        return match ($expression->type) {
            Type::AND => DoctrineCompositeExpression::TYPE_AND,
            Type::OR => DoctrineCompositeExpression::TYPE_OR,
            Type::NOT => DoctrineCompositeExpression::TYPE_NOT,
        };
    }

    /**
     * @return array<Expression>
     */
    private function getCompositeExpressionClauses(CompositeExpression $expression): array
    {
        if ($expression instanceof AndExpression || $expression instanceof OrExpression) {
            return $expression->clauses;
        }

        /** @var NotExpression $expression */
        return [$expression->clause];
    }

    /**
     * @param array<string, string> $criteriaToDoctrineFields
     * @return null|array<string, string>
     */
    private function convertOrder(?Order $order, array $criteriaToDoctrineFields): ?array
    {
        if (is_null($order)) {
            return null;
        }

        $orderByFieldName = $order->orderBy;

        if (array_key_exists($orderByFieldName, $criteriaToDoctrineFields)) {
            $orderByFieldName = $criteriaToDoctrineFields[$orderByFieldName];
        }

        return [
            $orderByFieldName => $order->orderType->value,
        ];
    }
}
