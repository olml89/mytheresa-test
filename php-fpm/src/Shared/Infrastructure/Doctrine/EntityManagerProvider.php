<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Exception\TypesException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use olml89\MyTheresaTest\Shared\Infrastructure\DatabaseConfig;

final readonly class EntityManagerProvider
{
    public function __construct(
        private DatabaseConfig $databaseConfig,
    ) {
    }

    /**
     * @throws Exception
     * @throws TypesException
     */
    public function provide(): EntityManagerInterface
    {
        // @TODO: don't hardcode this
        $rootDir = dirname(__DIR__, levels: 4);

        /**
         * Add custom types
         */
        $typeIterator = new TypeIterator($rootDir . '/src');

        foreach ($typeIterator as $typeInfo) {
            if (!Type::hasType($typeInfo->name())) {
                Type::addType($typeInfo->name(), $typeInfo->fullQualifiedClassName());
            }
        }

        /**
         * Add entity mappings
         */
        $entityIterator = new EntityIterator($rootDir . '/src');
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
        $configuration->setProxyDir($rootDir . '/var/proxies');
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
