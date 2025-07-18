<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Application;

use olml89\MyTheresaTest\Product\Application\PricePresenter;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\DiscountedPrice;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Discount\DiscountFactory;

#[CoversClass(PricePresenter::class)]
final class PricePresenterTest extends TestCase
{
    public function testItShowsNullPercentageAndOriginalSameAsFinalIfItHasNotADiscount(): void
    {
        $originalPrice = new OriginalPrice(50000, Currency::EUR);
        $discountedPrice = new DiscountedPrice($originalPrice, discount: null);

        $presenter = new PricePresenter($discountedPrice);

        self::assertEquals(null, $presenter->discount_percentage);
        self::assertEquals(50000, $presenter->original);
        self::assertEquals(50000, $presenter->final);
    }

    public function testItShowsPercentageAndDiscountedFinalIfItHasADiscount(): void
    {
        $originalPrice = new OriginalPrice(50000, Currency::EUR);
        $discount = DiscountFactory::create(percentage: new Percentage(50));
        $discountedPrice = new DiscountedPrice($originalPrice, discount: $discount);

        $presenter = new PricePresenter($discountedPrice);

        self::assertEquals('50%', $presenter->discount_percentage);
        self::assertEquals(50000, $presenter->original);
        self::assertEquals(25000, $presenter->final);
    }
}
