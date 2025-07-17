<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class FixtureLoader
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function load(SharedFixtureInterface ...$fixtures): void
    {
        $loader = new Loader();
        $purger = new ORMPurger($this->entityManager);
        $executor = new ORMExecutor($this->entityManager, $purger);

        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }

        $executor->execute($loader->getFixtures());
    }
}
