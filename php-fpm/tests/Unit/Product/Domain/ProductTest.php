<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Domain;

use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Discount\DiscountFactory;
use Tests\Unit\Product\Helpers\ProductFactory;

final class ProductTest extends TestCase
{
    /**
     * @return array<int, array{OriginalPrice, Discount[], ?Percentage}>
     */
    public static function provideOriginalPriceDiscountsAndExpectedDiscountPercentage(): array
    {
        return [
            [
                new OriginalPrice(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(30)),
                ],
                new Percentage(30),
            ],
            [
                new OriginalPrice(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(30)),
                    DiscountFactory::create(percentage: new Percentage(40)),
                ],
                new Percentage(40),
            ],
            [
                new OriginalPrice(89000, Currency::EUR),
                [],
                null,
            ],
            [
                new OriginalPrice(89000, Currency::EUR),
                [
                    DiscountFactory::create(percentage: new Percentage(100)),
                ],
                new Percentage(100),
            ],
        ];
    }

    /**
     * @param Discount[] $discounts
     */
    #[DataProvider('provideOriginalPriceDiscountsAndExpectedDiscountPercentage')]
    public function testItAppliesHighestDiscount(OriginalPrice $originalPrice, array $discounts, ?Percentage $expectedPercentageDiscount): void
    {
        $product = ProductFactory::create(price: $originalPrice);

        foreach ($discounts as $discount) {
            $product->addDiscount($discount);
        }

        $discountedPrice = $product->discountedPrice();

        self::assertEquals($expectedPercentageDiscount, $discountedPrice->percentageDiscount());
    }
}
