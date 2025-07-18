<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Application;

use olml89\MyTheresaTest\Product\Application\Filter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Filter::class)]
final class FilterTest extends TestCase
{
    /**
     * @return array<int, array{?int, int}>
     */
    public static function provideLimits(): array
    {
        return [
            [null, Filter::MAX_LIMIT],
            [-1, Filter::MAX_LIMIT],
            [1, 1],
            [2, 2],
            [3, 3],
            [4, 4],
            [5, 5],
            [6, Filter::MAX_LIMIT],
        ];
    }

    #[DataProvider('provideLimits')]
    public function testItSanitizesLimit(?int $limit, int $expectedLimit): void
    {
        $filter = new Filter(limit: $limit, specification: null);

        self::assertEquals($expectedLimit, $filter->limit);
    }
}
