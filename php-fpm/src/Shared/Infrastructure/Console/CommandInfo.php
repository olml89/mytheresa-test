<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Console;

use DI\Container;
use Exception;
use olml89\MyTheresaTest\Shared\Infrastructure\ItGetsFullQualifiedClassName;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;

final readonly class CommandInfo
{
    use ItGetsFullQualifiedClassName;

    private function __construct(
        private Command $command,
    ) {
    }

    public static function create(Container $container, SplFileInfo $file): ?self
    {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            return null;
        }

        $className = self::getFullQualifiedClassName($file->getRealPath());

        if (is_null($className) || !class_exists($className) || !is_subclass_of($className, Command::class)) {
            return null;
        }

        try {
            /** @var Command $command */
            $command = $container->get($className);

            return new self($command);
        } catch (Exception) {
            return null;
        }
    }

    public function command(): Command
    {
        return $this->command;
    }
}
