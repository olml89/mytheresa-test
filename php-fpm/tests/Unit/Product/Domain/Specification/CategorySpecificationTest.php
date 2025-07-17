<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Domain\Specification;

use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\Specification\CategorySpecification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Helpers\ProductFactory;

#[CoversClass(CategorySpecification::class)]
final class CategorySpecificationTest extends TestCase
{
    /**
     * @return array<int, array{Product, Category, bool}>
     */
    public static function provideProductCategoryAndIsSatisfied(): array
    {
        return [
            [
                ProductFactory::create(category: Category::Boots),
                Category::Boots,
                true,
            ],
            [
                ProductFactory::create(category: Category::Boots),
                Category::Sandals,
                false,
            ],
            [
                ProductFactory::create(category: Category::Boots),
                Category::Sneakers,
                false,
            ],
            [
                ProductFactory::create(category: Category::Sandals),
                Category::Sandals,
                true,
            ],
            [
                ProductFactory::create(category: Category::Sandals),
                Category::Sneakers,
                false,
            ],
            [
                ProductFactory::create(category: Category::Sandals),
                Category::Boots,
                false,
            ],
            [
                ProductFactory::create(category: Category::Sneakers),
                Category::Sneakers,
                true,
            ],
            [
                ProductFactory::create(category: Category::Sneakers),
                Category::Sandals,
                false,
            ],
            [
                ProductFactory::create(category: Category::Sneakers),
                Category::Boots,
                false,
            ],
        ];
    }

    #[DataProvider('provideProductCategoryAndIsSatisfied')]
    public function testItIsSatisfiedByProduct(Product $product, Category $category, bool $isSatisfied): void
    {
        $categorySpecification = new CategorySpecification($category);
        $result = $categorySpecification->isSatisfiedBy($product);

        self::assertEquals($isSatisfied, $result);
    }
}
