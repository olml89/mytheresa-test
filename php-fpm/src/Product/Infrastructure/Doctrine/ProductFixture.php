<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use olml89\MyTheresaTest\Product\Domain\Category;
use olml89\MyTheresaTest\Product\Domain\Product;

final class ProductFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            sku: '000001',
            name: 'BV Lean leather ankle boots',
            category: Category::Boots,
            price: 89000,
        );

        $manager->persist($product);
        $manager->flush();
    }
}
