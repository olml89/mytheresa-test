<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use olml89\MyTheresaTest\Shared\Infrastructure\ItGetsFullQualifiedClassName;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;

final readonly class FixtureInfo
{
    use ItGetsFullQualifiedClassName;

    private function __construct(
        private string $name,
        private SharedFixtureInterface $fixture,
    ) {
    }

    public static function create(SplFileInfo $file): ?self
    {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            return null;
        }

        $className = self::getFullQualifiedClassName($file->getRealPath());

        if (is_null($className) || !class_exists($className)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($className);

        if ($reflectionClass->isAbstract() || !$reflectionClass->implementsInterface(SharedFixtureInterface::class)) {
            return null;
        }

        $name = $reflectionClass->getShortName();

        try {
            /** @var SharedFixtureInterface $fixture */
            $fixture = $reflectionClass->newInstance();

            return new self($name, $fixture);
        } catch (ReflectionException) {
            return null;
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fixture(): SharedFixtureInterface
    {
        return $this->fixture;
    }
}
