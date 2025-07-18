<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain\Criteria;

interface Specification
{
    public function criteria(): Criteria;
}
