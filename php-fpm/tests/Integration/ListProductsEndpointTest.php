<?php

declare(strict_types=1);

namespace Integration;

use olml89\MyTheresaTest\Product\Domain\ProductRepository;
use olml89\MyTheresaTest\Product\Infrastructure\Doctrine\ProductFixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\UriFactory;
use Tests\Unit\Product\Helpers\InMemoryProductRepository;

final class ListProductsEndpointTest extends TestCase
{
    /** @var App<ContainerInterface> */
    private App $app;

    protected function setUp(): void
    {
        $this->app = $this->loadApp();

        $productRepository = new InMemoryProductRepository(
            ...new ProductFixture()->products(),
        );

        $this->app->getContainer()?->set(ProductRepository::class, $productRepository);
    }

    /**
     * @return App<ContainerInterface>
     */
    private function loadApp(): App
    {
        return require dirname(__DIR__, 2) . '/bootstrap/app.php';
    }

    /**
     * @return array<int, array{string, array<string, mixed>}>
     */
    public static function provideQueryFilterAndExpectedResults(): array
    {
        return [
            [
                '',
                [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 89000,
                            'final' => 62300,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000002',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 99000,
                            'final' => 69300,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 71000,
                            'final' => 49700,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000004',
                        'name' => 'Naima embellished suede sandals',
                        'category' => 'sandals',
                        'price' => [
                            'original' => 79500,
                            'final' => 79500,
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000005',
                        'name' => 'Nathane leather sneakers',
                        'category' => 'sneakers',
                        'price' => [
                            'original' => 59000,
                            'final' => 59000,
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                ]
            ],
            [
                '?limit=1',
                [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 89000,
                            'final' => 62300,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                ]
            ],
            [
                '?category=boots',
                [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 89000,
                            'final' => 62300,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000002',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 99000,
                            'final' => 69300,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 71000,
                            'final' => 49700,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                ]
            ],
            [
                '?priceLessThan=75000',
                [
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => 71000,
                            'final' => 49700,
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000005',
                        'name' => 'Nathane leather sneakers',
                        'category' => 'sneakers',
                        'price' => [
                            'original' => 59000,
                            'final' => 59000,
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                ]
            ],
            [
                '?category=sandals&priceLessThan=75000',
                [

                ]
            ],
            [
                '?category=sneakers&priceLessThan=75000',
                [
                    [
                        'sku' => '000005',
                        'name' => 'Nathane leather sneakers',
                        'category' => 'sneakers',
                        'price' => [
                            'original' => 59000,
                            'final' => 59000,
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * @param array<array<string, mixed>> $expectedResults
     */
    #[DataProvider('provideQueryFilterAndExpectedResults')]
    public function testItListsProducts(string $queryFilter, array $expectedResults): void
    {
        $uri = new UriFactory()->createUri('/products' . $queryFilter);
        $request = new ServerRequestFactory()->createServerRequest('GET', $uri);

        $response = $this->app->handle($request);

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals(
            json_encode($expectedResults),
            (string)$response->getBody()
        );
    }
}
