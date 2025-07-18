<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;

final readonly class Filter
{
    public const int MAX_LIMIT = 5;

    public int $limit;
    public ?ProductSpecification $specification;

    public function __construct(?int $limit = null, ?ProductSpecification $specification = null)
    {
        $this->limit = is_null($limit) || $limit < 1 || $limit > self::MAX_LIMIT ? self::MAX_LIMIT : $limit;
        $this->specification = $specification;
    }
}
