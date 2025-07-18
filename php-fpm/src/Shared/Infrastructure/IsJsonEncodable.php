<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure;

use JsonException;
use JsonSerializable;
use Stringable;

/**
 * @mixin JsonSerializable
 * @mixin Stringable
 */
trait IsJsonEncodable
{
    /**
     * @throws JsonException
     */
    public function jsonEncode(): string
    {
        return json_encode($this->jsonSerialize(), flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return $this->jsonEncode();
    }
}
