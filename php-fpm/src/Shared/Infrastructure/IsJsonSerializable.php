<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure;

use JsonSerializable;

/**
 * @mixin JsonSerializable
 */
trait IsJsonSerializable
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
