<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use Mperonnet\ImgProxy\Support\GravityType;
use PHPUnit\Framework\TestCase;

class ExtendTest extends TestCase
{
    /**
     * @dataProvider exampleData
     */
    public function testCreate(bool $extend, Gravity|string|null $gravity, string $expected): void
    {
        $opt = new Extend($extend, $gravity);

        $this->assertSame($expected, (string) $opt);
        $this->assertEquals($opt, eval('return '.var_export($opt, true).';'));
    }

    public function testCreateDefault(): void
    {
        $opt = new Extend();
        $this->assertSame('ex:1', (string) $opt);
    }

    /**
     * @dataProvider invalidGravityData
     */
    public function testCreateFail(string $gravity, string $message): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        new Extend(true, $gravity);
    }

    /**
     * @return array[]
     */
    public function exampleData(): array
    {
        return [
            [true, null, 'ex:1'],
            [false, null, 'ex:0'],
            [true, 'ce', 'ex:1:ce'],
            [true, new Gravity(GravityType::CENTER), 'ex:1:ce'],
            [false, 'ce:0:0', 'ex:0:ce:0:0'],
            [false, 'ce:200:250', 'ex:0:ce:200:250'],
            [false, new Gravity(GravityType::CENTER, 200, 250), 'ex:0:ce:200:250'],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidGravityData(): array
    {
        return [
            ['foo', 'Invalid gravity type: foo'],
            ['ce:bar:0', 'Gravity X should be numeric'],
            ['ce:0:baz', 'Gravity Y should be numeric'],
            ['ce:-100:0', 'Invalid gravity X: -100'],
            ['ce:0:-500', 'Invalid gravity Y: -500'],
        ];
    }
}
