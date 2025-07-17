<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain;

final readonly class ApplicationContext
{
    public function __construct(
        public Environment $environment,
        public string $rootDir,
    ) {
    }
}
