<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use olml89\MyTheresaTest\Product\Domain\Currency;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Discount\DumbDiscount;

#[CoversClass(Price::class)]
final class PriceTest extends TestCase
{
    public function testItAppliesDiscount(): void
    {
        $price = new Price(100, Currency::EUR);
        $discount = new DumbDiscount(new Percentage(0));

        self::assertEquals($discount, $price->applyDiscount($discount)->discount);
    }

    public function testItCalculates(): void
    {
        $price = new Price(100, Currency::EUR);
        $discount = new DumbDiscount(new Percentage(10));

        self::assertEquals(90, $price->applyDiscount($discount)->calculate());
    }
}
