<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Discount;

use Faker\Factory;
use Faker\Generator;
use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Product;
use Ramsey\Uuid\Uuid;
use Tests\Unit\Product\ProductFactory;

final class DiscountFactory
{
    private static ?Generator $faker = null;

    private static function faker(): Generator
    {
        if (is_null(self::$faker)) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }

    public static function create(
        ?string $name = null,
        ?Percentage $percentage = null,
        ?Product $product = null,
    ): Discount {
        return new Discount(
            id: Uuid::fromString(self::faker()->uuid()),
            name: $name ?? self::faker()->sentence(),
            percentage: $percentage ?? new Percentage(self::faker()->numberBetween(1, 100)),
            product: $product ?? ProductFactory::create(),
        );
    }
}
