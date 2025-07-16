<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Doctrine;

use FilesystemIterator;
use IteratorIterator;
use OuterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class FixtureIterator extends IteratorIterator implements OuterIterator
{
    private readonly RecursiveIteratorIterator $innerIterator;
    private readonly EntityInfo $entityInfo;

    public function __construct(EntityInfo $entityInfo)
    {
        $this->entityInfo = $entityInfo;

        $directoryIterator = new RecursiveDirectoryIterator(
            $this->entityInfo->dirname(),
            flags: FilesystemIterator::SKIP_DOTS,
        );

        $this->innerIterator = new RecursiveIteratorIterator($directoryIterator);

        parent::__construct($this->innerIterator);
    }

    public function valid(): bool
    {
        while ($this->innerIterator->valid()) {
            $file = $this->innerIterator->current();

            if (!is_null(FixtureInfo::create($file))) {
                return true;
            }

            $this->innerIterator->next();
        }

        return false;
    }

    public function key(): int
    {
        return $this->innerIterator->key();
    }

    public function current(): FixtureInfo
    {
        return FixtureInfo::create($this->innerIterator->current());
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
