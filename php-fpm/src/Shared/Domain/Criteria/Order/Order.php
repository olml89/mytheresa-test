<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria\Order;

final readonly class Order
{
    public function __construct(
        public string $orderBy,
        public OrderType $orderType,
    ) {
    }
}
