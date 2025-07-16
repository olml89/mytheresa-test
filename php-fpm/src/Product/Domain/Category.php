<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Domain;

enum Category: string
{
    case Boots = 'boots';
    case Sandals = 'sandals';
    case Sneakers = 'sneakers';
}
