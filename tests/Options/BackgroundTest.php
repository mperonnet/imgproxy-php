<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use Mperonnet\ImgProxy\Support\Color;
use PHPUnit\Framework\TestCase;

class BackgroundTest extends TestCase
{
    /**
     * @dataProvider validData
     * @param array<int> $colorArgs RGB color components [red, green, blue]
     */
    public function testCreate(array $colorArgs, string $expected): void
    {
        $color = $this->createColor($colorArgs);
        $opt = new Background($color);

        $this->assertSame($expected, (string) $opt);
        $this->assertEquals($opt, eval('return '.var_export($opt, true).';'));
    }

    /**
     * @dataProvider validHexData
     */
    public function testCreateFromHex(string $hex, string $expected): void
    {
        $color = Color::fromHex($hex);
        $opt = new Background($color);

        $this->assertSame($expected, (string) $opt);
    }

    /**
     * @dataProvider validRgbStringData
     */
    public function testCreateFromRgbString(string $rgbString, string $expected): void
    {
        $color = Color::fromRgbString($rgbString);
        $opt = new Background($color);

        $this->assertSame($expected, (string) $opt);
    }

    /**
     * @dataProvider invalidColorComponentsData
     */
    public function testCreateFailWithInvalidComponents(int $red, int $green, int $blue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Background(new Color($red, $green, $blue));
    }

    /**
     * Creates a Color instance from array args
     *
     * @param array<int> $args RGB color components [red, green, blue]
     * @return Color
     */
    private function createColor(array $args): Color
    {
        return new Color($args[0], $args[1], $args[2]);
    }

    /**
     * @return array[]
     */
    public function validData(): array
    {
        return [
            [[255, 204, 0], 'bg:ffcc00'],
            [[255, 100, 255], 'bg:ff64ff'],
            [[10, 20, 30], 'bg:0a141e'],
        ];
    }

    /**
     * @return array[]
     */
    public function validHexData(): array
    {
        return [
            ['FFCC00', 'bg:ffcc00'],
            ['ff64ff', 'bg:ff64ff'],
            ['0a141e', 'bg:0a141e'],
        ];
    }

    /**
     * @return array[]
     */
    public function validRgbStringData(): array
    {
        return [
            ['255:204:0', 'bg:255:204:0'],
            ['255:100:255', 'bg:255:100:255'],
            ['10:20:30', 'bg:10:20:30'],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidColorComponentsData(): array
    {
        return [
            [-10, 20, 30],
            [10, -20, 30],
            [10, 20, -30],
            [256, 255, 100],
            [100, 256, 100],
            [100, 100, 256],
        ];
    }
}
