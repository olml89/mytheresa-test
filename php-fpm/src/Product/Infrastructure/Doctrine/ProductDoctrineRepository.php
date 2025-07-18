<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\MyTheresaTest\Product\Domain\Product;
use olml89\MyTheresaTest\Product\Domain\ProductRepository;
use olml89\MyTheresaTest\Product\Domain\Specification\ProductSpecification;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;

/**
 * @extends EntityRepository<Product>
 */
final class ProductDoctrineRepository extends EntityRepository implements ProductRepository
{
    private readonly DoctrineCriteriaConverter $doctrineCriteriaConverter;

    public function __construct(DoctrineCriteriaConverter $doctrineCriteriaConverter, EntityManagerInterface $em)
    {
        $this->doctrineCriteriaConverter = $doctrineCriteriaConverter;

        parent::__construct($em, new ClassMetadata(Product::class));
    }

    /**
     * @return Product[]
     */
    public function list(int $limit, ?ProductSpecification $specification): array
    {
        $criteria = is_null($specification)
            ? new Criteria()->setMaxResults($limit)
            : $this->doctrineCriteriaConverter->convert($specification->criteria())->setMaxResults($limit);

        /** @var Product[] */
        return $this->matching($criteria)->toArray();
    }
}
