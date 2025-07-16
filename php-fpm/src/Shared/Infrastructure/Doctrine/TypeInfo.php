<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Types\Type;
use olml89\MyTheresaTest\Shared\Infrastructure\ItGetsFullQualifiedClassName;
use ReflectionClass;
use SplFileInfo;

final readonly class TypeInfo
{
    use ItGetsFullQualifiedClassName;

    private function __construct(
        private string $name,
        private string $fullQualifiedClassName,
    ) {
    }

    public static function create(SplFileInfo $file): ?self
    {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            return null;
        }

        $className = self::getFullQualifiedClassName($file->getRealPath());

        if (is_null($className) || !class_exists($className) || !is_subclass_of($className, Type::class)) {
            return null;
        }

        $name = new ReflectionClass($className)->getShortName();

        return new self($name, $className);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullQualifiedClassName(): string
    {
        return $this->fullQualifiedClassName;
    }
}
