<?php

declare(strict_types=1);

namespace olml89\MyTheresaTest\Product\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\StringType;
use olml89\MyTheresaTest\Product\Domain\Price\Currency;
use Throwable;

final class CurrencyType extends StringType
{
    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if ($value instanceof Currency) {
            return $value->value;
        }

        /**
         * This is needed because of how Doctrine manages Backed Enums.
         * If we need a stricter control, use ValueObjects implementing Stringable.
         */
        if (is_string($value)) {
            return $value;
        }

        throw InvalidType::new(
            value: $value,
            toType: self::class,
            possibleTypes: [
                Currency::class,
                'string',
            ],
        );
    }

    /**
     * @throws ValueNotConvertible
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Currency
    {
        if (!is_string($value)) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
            );
        }

        try {
            return Currency::from($value);
        } catch (Throwable $e) {
            throw ValueNotConvertible::new(
                value: $value,
                toType: self::class,
                previous: $e,
            );
        }
    }
}
