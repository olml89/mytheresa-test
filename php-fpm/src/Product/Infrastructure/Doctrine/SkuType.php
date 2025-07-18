<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\StringType;
use olml89\MyTheresaTest\Product\Domain\Sku;
use Throwable;

final class SkuType extends StringType
{
    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Sku)) {
            throw InvalidType::new(
                value: $value,
                toType: self::class,
                possibleTypes: [
                    Sku::class,
                ],
            );
        }

        return (string)$value;
    }

    /**
     * @throws ValueNotConvertible
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Sku
    {
        if (!is_string($value)) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
            );
        }

        try {
            return new Sku($value);
        } catch (Throwable $e) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
                previous: $e,
            );
        }
    }
}
