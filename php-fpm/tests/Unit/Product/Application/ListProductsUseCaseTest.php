<?php

declare(strict_types=1);

namespace Tests\Unit\Product\Application;

use olml89\MyTheresaTest\Product\Application\Filter;
use olml89\MyTheresaTest\Product\Application\ListProductsUseCase;
use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\Specification\CategorySpecification;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductAndSpecification;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductLessThanSpecification;
use olml89\MyTheresaTest\Product\Infrastructure\Doctrine\ProductFixture;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Helpers\InMemoryProductRepository;
use Tests\Unit\Product\Helpers\ProductFactory;

#[CoversClass(ListProductsUseCase::class)]
final class ListProductsUseCaseTest extends TestCase
{
    private function createListUseCase(Product ...$products): ListProductsUseCase
    {
        $productRepository = new InMemoryProductRepository(...$products);

        return new ListProductsUseCase($productRepository);
    }

    /**
     * @return array<string, array{0: Product[], 1: Filter, 2: Product[]}>
     */
    public static function provideProductsFilterAndExpectedProducts(): array
    {
        $productFixtureProducts = new ProductFixture()->products();

        return [
            'no products, no listed products' => [
                [],
                new Filter(),
                [],
            ],
            'all products, no filter, listed products with default limit' => [
                $productFixtureProducts,
                new Filter(),
                array_slice($productFixtureProducts, offset: 0, length: Filter::MAX_LIMIT),
            ],
            'products filtered by category' => (function (): array {
                $boots = ProductFactory::create(category: Category::Boots);
                $sandals = ProductFactory::create(category: Category::Sandals);
                $sneakers = ProductFactory::create(category: Category::Sneakers);

                return [
                    [
                        $boots,
                        $sandals,
                        $sneakers,
                    ],
                    new Filter(specification: new CategorySpecification(Category::Sandals)),
                    [
                        $sandals
                    ],
                ];
            })(),
            'products filtered by price' => (function (): array {
                $price150 = ProductFactory::create(price: new OriginalPrice(150, Currency::EUR));
                $price151 = ProductFactory::create(price: new OriginalPrice(151, Currency::EUR));
                $price152 = ProductFactory::create(price: new OriginalPrice(152, Currency::EUR));
                $price153 = ProductFactory::create(price: new OriginalPrice(153, Currency::EUR));

                return [
                    [
                        $price150,
                        $price151,
                        $price152,
                        $price153,
                    ],
                    new Filter(specification: new ProductLessThanSpecification(new OriginalPrice(152, Currency::EUR))),
                    [
                        $price150,
                        $price151,
                    ],
                ];
            })(),
            'products filtered by category and price' => (function (): array {
                $cheapBoots = ProductFactory::create(
                    category: Category::Boots,
                    price: new OriginalPrice(150, Currency::EUR),
                );
                $cheapSandals = ProductFactory::create(
                    category: Category::Sandals,
                    price: new OriginalPrice(151, Currency::EUR),
                );
                $expensiveSandals = ProductFactory::create(
                    category: Category::Sandals,
                    price: new OriginalPrice(152, Currency::EUR),
                );
                $expensiveSneakers = ProductFactory::create(
                    category: Category::Sneakers,
                    price: new OriginalPrice(153, Currency::EUR),
                );

                return [
                    [
                        $cheapBoots,
                        $cheapSandals,
                        $expensiveSandals,
                        $expensiveSneakers,
                    ],
                    new Filter(specification: new ProductAndSpecification(
                        new CategorySpecification(Category::Sandals),
                        new ProductLessThanSpecification(new OriginalPrice(152, Currency::EUR)),
                    )),
                    [
                        $cheapSandals,
                    ],
                ];
            })()
        ];
    }

    /**
     * @param Product[] $products
     * @param Product[] $expectedProducts
     */
    #[DataProvider('provideProductsFilterAndExpectedProducts')]
    public function testItListsProducts(array $products, Filter $filter, array $expectedProducts): void
    {
        $list = $this->createListUseCase(...$products)->list($filter);

        self::assertEquals($list, $expectedProducts);
    }
}
