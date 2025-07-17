<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Currency;
use olml89\MyTheresaTest\Product\Domain\Price;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\Sku;

final class ProductFixture extends AbstractFixture
{
    /**
     * @return Product[]
     */
    private function createProducts(): array
    {
        return [
            new Product(
                sku: new Sku('000001'),
                name: 'BV Lean leather ankle boots',
                category: Category::Boots,
                price: new Price(89000, Currency::EUR),
            ),
            new Product(
                sku: new Sku('000002'),
                name: 'BV Lean leather ankle boots',
                category: Category::Boots,
                price: new Price(99000, Currency::EUR),
            ),
            new Product(
                sku: new Sku('000003'),
                name: 'Ashlington leather ankle boots',
                category: Category::Boots,
                price: new Price(71000, Currency::EUR),
            ),
            new Product(
                sku: new Sku('000004'),
                name: 'Naima embellished suede sandals',
                category: Category::Sandals,
                price: new Price(79500, Currency::EUR),
            ),
            new Product(
                sku: new Sku('000005'),
                name: 'Nathane leather sneakers',
                category: Category::Sneakers,
                price: new Price(59000, Currency::EUR),
            ),
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->createProducts() as $product) {
            $manager->persist($product);
        }

        $manager->flush();
    }
}
