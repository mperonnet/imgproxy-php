<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Support;

use PHPUnit\Framework\TestCase;

class GravityTypeTest extends TestCase
{
    /**
     * @dataProvider validData
     */
    public function testCreate(string $type): void
    {
        $gravity = new GravityType($type);
        $this->assertSame($type, $gravity->value());
    }

    /**
     * @dataProvider invalidData
     */
    public function testCreateFail(string $type): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("Invalid gravity type: %s", $type));

        new GravityType($type);
    }

    /**
     * @return array<array<string>>
     */
    public function validData(): array
    {
        return [
            ['ce'],
            ['no'],
            ['ea'],
            ['so'],
            ['we'],
            ['nowe'],
            ['noea'],
            ['sowe'],
            ['soea'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function invalidData(): array
    {
        return [
            ['eu'],
            ['foo'],
            ['bar'],
        ];
    }
}
