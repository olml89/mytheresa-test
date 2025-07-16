<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Doctrine;

use FilesystemIterator;
use IteratorIterator;
use OuterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class EntityIterator extends IteratorIterator implements OuterIterator
{
    private readonly RecursiveIteratorIterator $innerIterator;

    public function __construct(string $baseDir)
    {
        $directoryIterator = new RecursiveDirectoryIterator($baseDir, flags: FilesystemIterator::SKIP_DOTS);
        $this->innerIterator = new RecursiveIteratorIterator($directoryIterator);

        parent::__construct($this->innerIterator);
    }

    public function getInnerIterator(): RecursiveIteratorIterator
    {
        return $this->innerIterator;
    }

    public function valid(): bool
    {
        while ($this->innerIterator->valid()) {
            $file = $this->innerIterator->current();

            if (!is_null(EntityInfo::create($file))) {
                return true;
            }

            $this->innerIterator->next();
        }

        return false;
    }

    public function key(): string
    {
        return $this->innerIterator->key();
    }

    public function current(): EntityInfo
    {
        return EntityInfo::create($this->innerIterator->current());
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
