<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Console;

use olml89\MyTheresaTest\Shared\Domain\ApplicationContext;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine\EntityIterator;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine\FixtureIterator;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadFixturesCommand extends Command
{
    protected static string $defaultName = 'load:fixtures';

    public function __construct(
        private readonly ApplicationContext $applicationContext,
        private readonly FixtureLoader $fixtureLoader,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Load entity data fixtures.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityIterator = new EntityIterator($this->applicationContext->rootDir);
        $output->writeln('🔧 Loading entity data fixtures...');
        $loadedFixtures = 0;

        foreach ($entityIterator as $entityInfo) {
            $fixtureIterator = new FixtureIterator($entityInfo);

            foreach ($fixtureIterator as $fixture) {
                $output->writeln(
                    sprintf('🔧 Loading fixtures %s for entity %s', $fixture->name(), $entityInfo->name()),
                );

                $this->fixtureLoader->load($fixture->fixture());
                ++$loadedFixtures;
            }
        }

        $loadedFixtures === 0
            ? $output->writeln('✅ No fixtures found')
            : $output->writeln(sprintf('✅ %s fixtures loaded successfully', $loadedFixtures));

        return self::SUCCESS;
    }
}
