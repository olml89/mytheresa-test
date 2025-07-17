<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Discount\Discount;
use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use olml89\MyTheresaTest\Product\Domain\Price\OriginalPrice;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\Sku;
use Ramsey\Uuid\Uuid;

final class ProductFixture extends AbstractFixture
{
    /**
     * List of example Products defined in the original specifications.
     * Addition of an extra Product at the end to test that it does not exceed the list limit.
     *
     * @return Product[]
     */
    public function products(): array
    {
        $products = [];

        $products[] = $product = new Product(
            sku: new Sku('000001'),
            name: 'BV Lean leather ankle boots',
            category: Category::Boots,
            price: new OriginalPrice(89000, Currency::EUR),
        );
        $discount = new Discount(
            id: Uuid::uuid4(),
            name: 'Boots discount',
            percentage: new Percentage(30),
        );
        $product->addDiscount($discount);

        $products[] = $product = new Product(
            sku: new Sku('000002'),
            name: 'BV Lean leather ankle boots',
            category: Category::Boots,
            price: new OriginalPrice(99000, Currency::EUR),
        );
        $discount = new Discount(
            id: Uuid::uuid4(),
            name: 'Boots discount',
            percentage: new Percentage(30),
        );
        $product->addDiscount($discount);

        $products[] = $product = new Product(
            sku: new Sku('000003'),
            name: 'Ashlington leather ankle boots',
            category: Category::Boots,
            price: new OriginalPrice(71000, Currency::EUR),
        );
        $discount1 = new Discount(
            id: Uuid::uuid4(),
            name: 'Boots discount',
            percentage: new Percentage(30),
        );
        $discount2 = new Discount(
            id: Uuid::uuid4(),
            name: 'Product 000003 custom discount',
            percentage: new Percentage(15),
        );
        $product
            ->addDiscount($discount1)
            ->addDiscount($discount2);

        $products[] = new Product(
            sku: new Sku('000004'),
            name: 'Naima embellished suede sandals',
            category: Category::Sandals,
            price: new OriginalPrice(79500, Currency::EUR),
        );

        $products[] = new Product(
            sku: new Sku('000005'),
            name: 'Nathane leather sneakers',
            category: Category::Sneakers,
            price: new OriginalPrice(59000, Currency::EUR),
        );

        $products[] = new Product(
            sku: new Sku('000006'),
            name: 'Adidas sneakers',
            category: Category::Sneakers,
            price: new OriginalPrice(123000, Currency::EUR),
        );

        return $products;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->products() as $product) {
            $manager->persist($product);
        }

        $manager->flush();
    }
}
