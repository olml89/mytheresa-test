<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use JsonSerializable;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Infrastructure\IsJsonEncodable;
use Stringable;

final readonly class ProductsPresenter implements JsonSerializable, Stringable
{
    use IsJsonEncodable;

    /**
     * @var ProductPresenter[]
     */
    private array $productPresenters;

    public function __construct(Product ...$products)
    {
        $this->productPresenters = array_map(
            fn (Product $product): ProductPresenter => new ProductPresenter($product),
            $products,
        );
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return array_map(
            fn (ProductPresenter $productPresenter): array => $productPresenter->jsonSerialize(),
            $this->productPresenters,
        );
    }
}
