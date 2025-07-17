<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;

final readonly class Filter
{
    public const int MAX_LIMIT = 5;

    public function __construct(
        public int $limit = self::MAX_LIMIT,
        public ?ProductSpecification $specification = null,
    ) {
    }

    public function sanitize(): self
    {
        return new self(
            limit: $this->limit < 1 || $this->limit > self::MAX_LIMIT ? self::MAX_LIMIT : $this->limit,
            specification: $this->specification,
        );
    }
}
