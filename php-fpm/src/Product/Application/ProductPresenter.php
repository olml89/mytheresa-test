<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use JsonSerializable;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Shared\Infrastructure\IsJsonEncodable;
use olml89\MyTheresaTest\Shared\Infrastructure\IsJsonSerializable;
use Stringable;

final readonly class ProductPresenter implements JsonSerializable, Stringable
{
    use IsJsonSerializable;
    use IsJsonEncodable;

    public string $sku;
    public string $name;
    public string $category;
    public PricePresenter $price;

    public function __construct(Product $product)
    {
        $this->sku = (string)$product->sku();
        $this->name = $product->name();
        $this->category = $product->category()->value;
        $this->price = new PricePresenter($product->discountedPrice());
    }
}
