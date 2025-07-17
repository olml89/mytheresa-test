<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Domain\Price;

use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\DiscountedPrice;
use olml89\MyTheresaTest\Product\Domain\Price\InvalidPriceException;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Price\Price;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Discount\DiscountFactory;

#[CoversClass(OriginalPrice::class)]
final class OriginalPriceTest extends TestCase
{
    public function testItThrowsInvalidPriceExceptionIfItIsNegative(): void
    {
        $negative = -1;

        $this->expectExceptionObject(InvalidPriceException::negativePrice($negative));

        new OriginalPrice($negative, Currency::EUR);
    }

    /**
     * @return array<int, array{Price, Price, int}>
     */
    public static function provideMinuhendSubtrahendAndExpectedValue(): array
    {
        return [
            [
                new OriginalPrice(100, Currency::EUR),
                new OriginalPrice(50, Currency::EUR),
                50,
            ],
            [
                new OriginalPrice(100, Currency::EUR),
                new DiscountedPrice(
                    new OriginalPrice(50, Currency::EUR),
                    DiscountFactory::create(percentage: new Percentage(50)),
                ),
                75,
            ],
        ];
    }

    #[DataProvider('provideMinuhendSubtrahendAndExpectedValue')]
    public function testItSubtracts(Price $minuhend, Price $subtrahend, int $expectedValue): void
    {
        $result = $minuhend->subtract($subtrahend)->value();

        self::assertEquals($expectedValue, $result);
    }
}
