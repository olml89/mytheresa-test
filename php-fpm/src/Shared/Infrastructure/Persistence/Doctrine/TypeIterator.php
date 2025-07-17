<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use FilesystemIterator;
use IteratorIterator;
use olml89\MyTheresaTest\Shared\Infrastructure\Console\CommandInfo;
use OuterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * @template-extends IteratorIterator<string, TypeInfo, RecursiveIteratorIterator<RecursiveDirectoryIterator>>
 * @implements OuterIterator<string, TypeInfo>
 */
final class TypeIterator extends IteratorIterator implements OuterIterator
{
    /**
     * @var RecursiveIteratorIterator<RecursiveDirectoryIterator>
     */
    private readonly RecursiveIteratorIterator $innerIterator;

    public function __construct(string $baseDir)
    {
        $directoryIterator = new RecursiveDirectoryIterator($baseDir, flags: FilesystemIterator::SKIP_DOTS);
        $this->innerIterator = new RecursiveIteratorIterator($directoryIterator);

        parent::__construct($this->innerIterator);
    }

    public function valid(): bool
    {
        while ($this->innerIterator->valid()) {
            /** @var SplFileInfo $file */
            $file = $this->innerIterator->current();

            if (!is_null(TypeInfo::create($file))) {
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

    public function current(): TypeInfo
    {
        /** @var SplFileInfo $current $current */
        $current = $this->innerIterator->current();

        /**
         * We guarantee that it won't be null as we have called isValid() before
         *
         * @var TypeInfo
         */
        return TypeInfo::create($current);
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
