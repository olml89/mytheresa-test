<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Domain\Price;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
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

#[CoversClass(DiscountedPrice::class)]
final class DiscountedPriceTest extends TestCase
{
    public function testItThrowsInvalidPriceExceptionIfItIsNegative(): void
    {
        $negative = -1;

        $this->expectExceptionObject(InvalidPriceException::negativePrice($negative));

        new OriginalPrice($negative, Currency::EUR);
    }

    public function testItWrapsOriginalValues(): void
    {
        $originalPrice = new OriginalPrice(100, Currency::EUR);
        $discountedPrice = new DiscountedPrice($originalPrice, DiscountFactory::create());

        self::assertEquals(100, $discountedPrice->original());
        self::assertEquals(Currency::EUR, $discountedPrice->currency());
    }

    /**
     * @return array<int, array{0: OriginalPrice, 1: ?Discount, 2: ?Percentage, 3: int}>
     */
    public static function provideOriginalPriceAndDiscount(): array
    {
        return [
            [
                new OriginalPrice(100, Currency::EUR),
                null,
                null,
                100,
            ],
            [
                new OriginalPrice(100, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(30)),
                new Percentage(30),
                70,
            ],
        ];
    }

    #[DataProvider('provideOriginalPriceAndDiscount')]
    public function testItReturnsDiscountAffectedValues(
        OriginalPrice $original,
        ?Discount $discount,
        ?Percentage $expectedDiscountPercentage,
        int $expectedFinal,
    ): void {
        $discountedPrice = new DiscountedPrice($original, $discount);

        self::assertEquals($expectedDiscountPercentage, $discountedPrice->percentageDiscount());
        self::assertEquals($expectedFinal, $discountedPrice->value());
    }

    /**
     * @return array<int, array{Price, Price, int}>
     */
    public static function provideMinuhendSubtrahendAndExpectedValue(): array
    {
        return [
            [
                new DiscountedPrice(
                    new OriginalPrice(100, Currency::EUR),
                    DiscountFactory::create(percentage: new Percentage(50)),
                ),
                new OriginalPrice(25, Currency::EUR),
                25,
            ],
            [
                new DiscountedPrice(
                    new OriginalPrice(100, Currency::EUR),
                    DiscountFactory::create(percentage: new Percentage(50)),
                ),
                new DiscountedPrice(
                    new OriginalPrice(50, Currency::EUR),
                    DiscountFactory::create(percentage: new Percentage(50)),
                ),
                25,
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
