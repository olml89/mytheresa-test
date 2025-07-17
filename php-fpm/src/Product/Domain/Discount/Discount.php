<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain\Discount;

use olml89\MyTheresaTest\Product\Domain\Discount\Percentage\Percentage;
use olml89\MyTheresaTest\Product\Domain\Price;
use olml89\MyTheresaTest\Product\Domain\Product;
use Ramsey\Uuid\UuidInterface;

final class Discount
{
    public function __construct(
        private readonly UuidInterface $id,
        private string $name,
        private Percentage $percentage,
        private ?Product $product = null,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function percentage(): Percentage
    {
        return $this->percentage;
    }

    public function setPercentage(Percentage $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function product(): ?Product
    {
        return $this->product;
    }

    /**
     * @internal
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Conceptually, this returns the price of the discount, that will be subtracted from a final price.
     */
    public function price(Price $price): Price
    {
        return new Price(
            original: $this->percentage->calculate($price->original),
            currency: $price->currency,
        );
    }

    public function greaterThan(Discount $discount): bool
    {
        return $this->percentage->greaterThan($discount->percentage());
    }
}
