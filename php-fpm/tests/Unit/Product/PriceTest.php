<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use olml89\MyTheresaTest\Product\Domain\Currency;
use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\InvalidPriceException;
use olml89\MyTheresaTest\Product\Domain\Price;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Discount\DiscountFactory;

#[CoversClass(Price::class)]
final class PriceTest extends TestCase
{
    public function testItThrowsInvalidPriceExceptionIfItIsNegative(): void
    {
        $negative = -1;

        $this->expectExceptionObject(new InvalidPriceException($negative));

        new Price($negative, Currency::EUR);
    }

    /**
     * @return array<int, array{Price, Discount, Price}>
     */
    public static function providePriceAndDiscount(): array
    {
        return [
            [
                new Price(100, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(100)),
                new Price(0, Currency::EUR)
            ],
            [
                new Price(100, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(0)),
                new Price(100, Currency::EUR)
            ],
            [
                new Price(0, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(100)),
                new Price(0, Currency::EUR)
            ],
            [
                new Price(0, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(0)),
                new Price(0, Currency::EUR)
            ],
            [
                new Price(100, Currency::EUR),
                DiscountFactory::create(percentage: new Percentage(30)),
                new Price(70, Currency::EUR)
            ],
        ];
    }

    #[DataProvider('providePriceAndDiscount')]
    public function testItAppliesDiscount(Price $price, Discount $discount, Price $expectedFinalPrice): void
    {
        $result = $price->apply($discount);

        self::assertEquals($expectedFinalPrice, $result);
    }
}
