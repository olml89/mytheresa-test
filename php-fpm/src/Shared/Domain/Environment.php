<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain;

enum Environment: string
{
    case Development = 'development';
    case Production = 'production';
}
