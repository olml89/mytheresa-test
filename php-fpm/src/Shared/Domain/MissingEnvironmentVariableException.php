<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain;

use RuntimeException;

final class MissingEnvironmentVariableException extends RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('%s not found in $_ENV', $name));
    }
}
