<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Console;

use DI\Container;
use FilesystemIterator;
use IteratorIterator;
use olml89\MyTheresaTest\Shared\Domain\ApplicationContext;
use OuterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * @template-extends IteratorIterator<string, CommandInfo, RecursiveIteratorIterator<RecursiveDirectoryIterator>>
 * @implements OuterIterator<string, CommandInfo>
 */
final class CommandIterator extends IteratorIterator implements OuterIterator
{
    /**
     * @var RecursiveIteratorIterator<RecursiveDirectoryIterator>
     */
    private readonly RecursiveIteratorIterator $innerIterator;
    private readonly Container $container;

    public function __construct(ApplicationContext $applicationContext, Container $container)
    {
        $directoryIterator = new RecursiveDirectoryIterator(
            $applicationContext->rootDir . '/src',
            flags: FilesystemIterator::SKIP_DOTS,
        );

        $this->innerIterator = new RecursiveIteratorIterator($directoryIterator);
        $this->container = $container;

        parent::__construct($this->innerIterator);
    }

    public function valid(): bool
    {
        while ($this->innerIterator->valid()) {
            /** @var SplFileInfo $file */
            $file = $this->innerIterator->current();

            if (!is_null(CommandInfo::create($this->container, $file))) {
                return true;
            }

            $this->innerIterator->next();
        }

        return false;
    }

    public function key(): string
    {
        /** @var string */
        return $this->innerIterator->key();
    }

    public function current(): ?CommandInfo
    {
        /** @var SplFileInfo $current $current */
        $current = $this->innerIterator->current();

        return CommandInfo::create($this->container, $current);
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
