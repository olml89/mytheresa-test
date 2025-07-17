<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use olml89\MyTheresaTest\Product\Domain\Currency;
use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Discount\DiscountFactory;

final class ProductTest extends TestCase
{
    /**
     * @return array<int, array{Price, Discount[], Price}>>
     */
    public static function provideOriginalPriceDiscountsAndExpectedPrice(): array
    {
        return [
            [
                new Price(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(30)),
                ],
                new Price(62300, Currency::EUR),
            ],
            [
                new Price(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(30)),
                    DiscountFactory::create(percentage: new Percentage(40)),
                ],
                new Price(53400, Currency::EUR),
            ],
            [
                new Price(89000, Currency::EUR),
                [],
                new Price(89000, Currency::EUR),
            ],
            [
                new Price(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(100)),
                ],
                new Price(0, Currency::EUR),
            ],
        ];
    }

    #[DataProvider('provideOriginalPriceDiscountsAndExpectedPrice')]
    /**
     * @param Discount[] $discounts
     */
    public function testItCalculatesPrice(Price $originalPrice, array $discounts, Price $expectedPrice): void
    {
        $product = ProductFactory::create(price: $originalPrice);

        foreach ($discounts as $discount) {
            $product->addDiscount($discount);
        }

        self::assertEquals($expectedPrice, $product->price());
    }
}
