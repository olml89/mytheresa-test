<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Application;

use JsonSerializable;
use olml89\MyTheresaTest\Product\Domain\Price\DiscountedPrice;
use olml89\MyTheresaTest\Shared\Infrastructure\IsJsonEncodable;
use olml89\MyTheresaTest\Shared\Infrastructure\IsJsonSerializable;
use Stringable;

final readonly class PricePresenter implements JsonSerializable, Stringable
{
    use IsJsonSerializable;
    use IsJsonEncodable;

    public int $original;
    public int $final;
    public ?string $discount_percentage;
    public string $currency;

    public function __construct(DiscountedPrice $price)
    {
        $this->original = $price->original();
        $this->final = $price->value();
        $this->discount_percentage = $price->percentageDiscount()?->__toString();
        $this->currency = $price->currency()->value;
    }
}
