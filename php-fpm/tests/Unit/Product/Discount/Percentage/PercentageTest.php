<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Discount\Percentage;

use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\InvalidPercentageException;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Percentage::class)]
final class PercentageTest extends TestCase
{
    public function testItThrowsInvalidPercentageExceptionIfValueIsNegative(): void
    {
        $negative = -1;

        self::expectExceptionObject(InvalidPercentageException::negative($negative));

        new Percentage($negative);
    }

    public function testItThrowsInvalidPercentageExceptionIfValueIsBiggerThan100(): void
    {
        $bigger = 101;

        self::expectExceptionObject(InvalidPercentageException::tooBig($bigger));

        new Percentage($bigger);
    }

    /**
     * @return array<int, int[]>
     */
    public static function provideValues(): array
    {
        return [
            [0, 50, 0],
            [100, 50, 50],
            [50, 50, 25],
            // We expect a cast from 2.55 since it only works with ints
            [15, 17, 2],
        ];
    }

    #[DataProvider('provideValues')]
    public function testItCalculates(int $percentValue, int $value, int $expectedValue): void
    {
        $percentage = new Percentage($percentValue);
        $result = $percentage->calculate($value);

        self::assertEquals($expectedValue, $result);
    }
}
