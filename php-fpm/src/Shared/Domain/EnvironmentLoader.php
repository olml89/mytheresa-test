<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Domain;

interface EnvironmentLoader
{
    public function load(string $path): void;
    public function get(string $key, null|bool|int|float|string $default): null|bool|int|float|string;

    /** @throws MissingEnvironmentVariableException */
    public function string(string $key, ?string $default = null): string;

    /** @throws MissingEnvironmentVariableException */
    public function bool(string $key, ?bool $default = null): bool;

    /** @throws MissingEnvironmentVariableException */
    public function int(string $key, ?int $default = null): int;

    /** @throws MissingEnvironmentVariableException */
    public function float(string $key, ?float $default = null): float;
}
