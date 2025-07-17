<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Domain\Discount;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Discount::class)]
final class DiscountTest extends TestCase
{
    /**
     * @return array<int, array{OriginalPrice, Percentage, OriginalPrice}>
     */
    public static function providePrices(): array
    {
        return [
            [
                new OriginalPrice(100, Currency::EUR),
                new Percentage(0),
                new OriginalPrice(0, Currency::EUR),
            ],
            [
                new OriginalPrice(100, Currency::EUR),
                new Percentage(100),
                new OriginalPrice(100, Currency::EUR),
            ],
            [
                new OriginalPrice(100, Currency::EUR),
                new Percentage(15),
                new OriginalPrice(15, Currency::EUR),
            ],
        ];
    }

    #[DataProvider('providePrices')]
    public function testItAppliesToPrice(OriginalPrice $price, Percentage $discountPercentage, OriginalPrice $expectedPrice): void
    {
        $discount = DiscountFactory::create(percentage: $discountPercentage);
        $result = $discount->price($price);

        self::assertEquals($expectedPrice, $result);
    }

    /**
     * @return array<int, array{Discount, Discount, bool}>
     */
    public static function provideDiscounts(): array
    {
        return [
            [
                DiscountFactory::create(percentage: new Percentage(30)),
                DiscountFactory::create(percentage: new Percentage(30)),
                false,
            ],
            [
                DiscountFactory::create(percentage: new Percentage(30)),
                DiscountFactory::create(percentage: new Percentage(40)),
                false,
            ],
            [
                DiscountFactory::create(percentage: new Percentage(30)),
                DiscountFactory::create(percentage: new Percentage(20)),
                true,
            ],
        ];
    }

    #[DataProvider('provideDiscounts')]
    public function testGreaterThan(Discount $firstDiscount, Discount $secondDiscount, bool $firstIsGreater): void
    {
        $result = $firstDiscount->greaterThan($secondDiscount);

        self::assertEquals($firstIsGreater, $result);
    }
}
