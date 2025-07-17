<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use FilesystemIterator;
use IteratorIterator;
use OuterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * @template-extends IteratorIterator<string, FixtureInfo, RecursiveIteratorIterator<RecursiveDirectoryIterator>>
 * @implements OuterIterator<string, FixtureInfo>
 */
final class FixtureIterator extends IteratorIterator implements OuterIterator
{
    /**
     * @var RecursiveIteratorIterator<RecursiveDirectoryIterator>
     */
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
            /** @var SplFileInfo $file */
            $file = $this->innerIterator->current();

            if (!is_null(FixtureInfo::create($file))) {
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

    public function current(): ?FixtureInfo
    {
        /** @var SplFileInfo $current $current */
        $current = $this->innerIterator->current();

        return FixtureInfo::create($current);
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
