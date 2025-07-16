<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure\Doctrine;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use SplFileInfo;

final readonly class EntityInfo
{
    public const string EXTENSION = '.orm.xml';

    private string $name;
    private string $dirname;
    private string $baseNamespace;

    public function __construct(string $name, string $dirname, string $baseNamespace)
    {
        $this->name = $name;
        $this->dirname = $dirname;
        $this->baseNamespace = $baseNamespace;
    }

    public static function create(SplFileInfo $file): ?self
    {
        /**
         * Product.orm.xml
         * name: Product
         * real extension: .orm.xml
         */
        $name = $file->getBasename(suffix: self::EXTENSION);
        $realExtension = str_replace($name, replace: '', subject: $file->getBasename());

        if (!$file->isFile() || $realExtension !== self::EXTENSION) {
            return null;
        }

        $dirname = dirname($file->getPathname());

        /**
         * Get the namespace from the .orm.xml
         */
        $doc = new DOMDocument();
        $doc->load($file->getPathName());

        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('d', 'https://doctrine-project.org/schemas/orm/doctrine-mapping');
        $entityNodes = $xpath->query(expression: '//d:entity');

        if (!($entityNodes instanceof DOMNodeList) || $entityNodes->length === 0) {
            return null;
        }

        $entityNode = $entityNodes->item(index: 0);

        if (!$entityNode instanceof DOMElement || !$entityNode->hasAttribute(qualifiedName: 'name')) {
            return null;
        }

        /**
         * namespace of the class: olml89\MyTheresa\Product\Domain\Product
         * base namespace: olml89\MyTheresa\Product\Domain
         */
        $namespace = $entityNode->getAttribute(qualifiedName: 'name');
        $baseNamespace = substr($namespace, offset: 0, length: strrpos($namespace, needle: '\\'));

        return new self($name, $dirname, $baseNamespace);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function dirname(): string
    {
        return $this->dirname;
    }

    public function baseNamespace(): string
    {
        return $this->baseNamespace;
    }
}
