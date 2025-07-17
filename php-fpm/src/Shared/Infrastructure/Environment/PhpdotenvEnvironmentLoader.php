<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Environment;

use Dotenv\Dotenv;
use InvalidArgumentException;
use olml89\MyTheresaTest\Shared\Domain\EnvironmentLoader;
use olml89\MyTheresaTest\Shared\Domain\MissingEnvironmentVariableException;

final readonly class PhpdotenvEnvironmentLoader implements EnvironmentLoader
{
    /**
     * @throws MissingEnvironmentVariableException
     */
    public function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('env file not found at %s', $path));
        }

        $dotEnv = DotEnv::createImmutable($path);
        $dotEnv->load();
    }

    /**
     * @throws MissingEnvironmentVariableException
     */
    public function get(string $key, float|bool|int|string|null $default): null|bool|int|float|string
    {
        /** @var ?string $value */
        $value = $_ENV[$key] ?? null;

        if (is_null($value)) {
            return $default;
        }

        return match (true) {
            in_array($value, ['null', '(null)', 'NULL', '(NULL)'], strict: true) => null,
            default => $value,
        };
    }

    /**
     * @throws MissingEnvironmentVariableException
     */
    public function string(string $key, ?string $default = null): string
    {
        $value = $this->get($key, $default);

        if (is_null($value)) {
            throw new MissingEnvironmentVariableException($key);
        }

        return is_string($value) ? $value : (string)$value;
    }

    /**
     * @throws MissingEnvironmentVariableException
     */
    public function bool(string $key, ?bool $default = null): bool
    {
        $value = $this->get($key, $default);

        if (is_null($value)) {
            throw new MissingEnvironmentVariableException($key);
        }

        return is_bool($value) ? $value : (bool)$value;
    }

    /**
     * @throws MissingEnvironmentVariableException
     */
    public function int(string $key, ?int $default = null): int
    {
        $value = $this->get($key, $default);

        if (is_null($value)) {
            throw new MissingEnvironmentVariableException($key);
        }

        return is_int($value) ? $value : (int)$value;
    }

    /**
     * @throws MissingEnvironmentVariableException
     */
    public function float(string $key, ?float $default = null): float
    {
        $value = $this->get($key, $default);

        if (is_null($value)) {
            throw new MissingEnvironmentVariableException($key);
        }

        return is_float($value) ? $value : (float)$value;
    }
}
