<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Shared\Infrastructure;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

trait ItGetsFullQualifiedClassName
{
    private static function getFullQualifiedClassName(string $path): ?string
    {
        if (is_null($code = file_get_contents($path) ?: null)) {
            return null;
        }

        $parser = new ParserFactory()->createForNewestSupportedVersion();
        $ast = $parser->parse($code);

        if (is_null($ast)) {
            return null;
        }

        $traverser = new NodeTraverser();

        $visitor = new class () extends NodeVisitorAbstract {
            public ?string $namespace = null;
            public ?string $className = null;

            public function enterNode(Node $node): null
            {
                if ($node instanceof Node\Stmt\Namespace_) {
                    $this->namespace = $node->name?->toString();
                }

                if ($node instanceof Node\Stmt\Class_ && isset($node->name)) {
                    $this->className = $node->name->toString();
                }

                return null;
            }
        };

        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        if (is_null($visitor->className)) {
            return null;
        }

        return !is_null($visitor->namespace)
            ? $visitor->namespace . '\\' . $visitor->className
            : $visitor->className;
    }
}
