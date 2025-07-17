<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use olml89\MyTheresaTest\Product\Domain\InvalidSkuException;
use olml89\MyTheresaTest\Product\Domain\Sku;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Sku::class)]
final class SkuTest extends TestCase
{
    public function testItThrowsInvalidSkuExceptionIfLengthIsLowerThan6(): void
    {
        $short = '12345';

        self::expectExceptionObject(new InvalidSkuException($short));

        new Sku($short);
    }

    public function testItThrowsInvalidSkuExceptionIfLengthIsGreaterThan6(): void
    {
        $short = '1234567';

        self::expectExceptionObject(new InvalidSkuException($short));

        new Sku($short);
    }

    public function testItThrowsInvalidSkuExceptionIfItIsNotNumeric(): void
    {
        $short = '12345a';

        self::expectExceptionObject(new InvalidSkuException($short));

        new Sku($short);
    }

    public function testItStringifies(): void
    {
        $value = '123456';
        $sku = new Sku($value);

        self::assertEquals($value, (string)$sku);
    }
}
