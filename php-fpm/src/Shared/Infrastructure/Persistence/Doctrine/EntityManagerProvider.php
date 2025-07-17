<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Exception\TypesException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use olml89\MyTheresaTest\Shared\Domain\ApplicationContext;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\DatabaseConfig;
use Ramsey\Uuid\Doctrine\UuidType;

final readonly class EntityManagerProvider
{
    public function __construct(
        private ApplicationContext $applicationContext,
        private DatabaseConfig $databaseConfig,
    ) {
    }

    /**
     * @throws Exception
     * @throws TypesException
     */
    public function provide(): EntityManagerInterface
    {
        /**
         * Manually add UUID type
         */
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', UuidType::class);
        }

        /**
         * Add custom types
         */
        $typeIterator = new TypeIterator($this->applicationContext->rootDir . '/src');

        foreach ($typeIterator as $typeInfo) {
            if (!Type::hasType($typeInfo->name())) {
                Type::addType($typeInfo->name(), $typeInfo->fullQualifiedClassName());
            }
        }

        /**
         * Add entity mappings
         */
        $entityIterator = new EntityIterator($this->applicationContext->rootDir . '/src');
        $namespaces = [];

        foreach ($entityIterator as $entityInfo) {
            $namespaces[$entityInfo->dirname()] = $entityInfo->baseNamespace();
        }

        $driver = new SimplifiedXmlDriver(
            $namespaces,
            fileExtension: EntityInfo::EXTENSION,
            isXsdValidationEnabled: false,
        );

        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl($driver);
        $configuration->setProxyDir($this->applicationContext->rootDir . '/var/proxies');
        $configuration->setProxyNamespace('DoctrineProxy');

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'host' => $this->databaseConfig->host,
            'port' => $this->databaseConfig->port,
            'dbname' => $this->databaseConfig->database,
            'user' => $this->databaseConfig->username,
            'password' => $this->databaseConfig->password,
        ]);

        return new EntityManager($connection, $configuration);
    }
}
