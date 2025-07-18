<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Helpers;

use Faker\Factory;
use Faker\Generator;
use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\Sku;

final class ProductFactory
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
        ?Sku $sku = null,
        ?string $name = null,
        ?Category $category = null,
        ?OriginalPrice $price = null,
    ): Product {
        /** @var Category $category */
        $category = $category ?? self::faker()->randomElement(Category::cases());

        return new Product(
            sku: $sku ?? new Sku(self::faker()->numerify('######')),
            name: $name ?? self::faker()->sentence(),
            category: $category,
            price: $price ?? new OriginalPrice(self::faker()->numberBetween(100, 100000), Currency::EUR),
        );
    }
}
