<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Support;

use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    /**
     * @dataProvider hexColorData
     */
    public function testCreateFromHex(string $hexColor, string $expectedValue): void
    {
        $color = Color::fromHex($hexColor);
        $this->assertSame($expectedValue, $color->value());
    }

    /**
     * @dataProvider rgbStringColorData
     */
    public function testCreateFromRgbString(string $rgbString, string $expectedValue): void
    {
        $color = Color::fromRgbString($rgbString);
        $this->assertSame($expectedValue, $color->asRgb());
    }

    /**
     * @dataProvider rgbComponentsData
     */
    public function testCreateFromRgbComponents(int $red, int $green, int $blue, string $expectedHex): void
    {
        $color = new Color($red, $green, $blue);
        $this->assertSame($expectedHex, $color->value());
    }

    /**
     * @dataProvider invalidHexData
     */
    public function testCreateFromHexFail(string $invalidHex): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Color::fromHex($invalidHex);
    }

    /**
     * @dataProvider invalidRgbStringData
     */
    public function testCreateFromRgbStringFail(string $invalidRgb): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Color::fromRgbString($invalidRgb);
    }

    /**
     * @dataProvider invalidRgbComponentsData
     */
    public function testCreateFromInvalidRgbComponents(int $red, int $green, int $blue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Color($red, $green, $blue);
    }

    public function testCreateWithAlpha(): void
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertSame('ffcc00:0.5', $color->value());
    }

    public function testAsRgb(): void
    {
        $color = new Color(255, 204, 0);
        $this->assertSame('255:204:0', $color->asRgb());
    }

    public function testAsHex(): void
    {
        $color = new Color(255, 204, 0);
        $this->assertSame('ffcc00', $color->asHex());
    }

    /**
     * @return array<array<mixed>>
     */
    public function hexColorData(): array
    {
        return [
            ['ffcc00', 'ffcc00'],
            ['EBf0f4', 'ebf0f4'],
            ['123', '112233'], // Shorthand hex
            ['#ffcc00', 'ffcc00'], // With hash
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public function rgbStringColorData(): array
    {
        return [
            ['255:255:128', '255:255:128'],
            ['10:20:30', '10:20:30'],
            ['0:0:0', '0:0:0'],
            ['255:255:255', '255:255:255'],
        ];
    }

    /**
     * @return array<array<mixed>>
     */
    public function rgbComponentsData(): array
    {
        return [
            [255, 204, 0, 'ffcc00'],
            [10, 20, 30, '0a141e'],
            [0, 0, 0, '000000'],
            [255, 255, 255, 'ffffff'],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function invalidHexData(): array
    {
        return [
            ['ffcc00aa'], // Too long
            ['xyz'], // Invalid characters
            ['ff00'], // Wrong length
            ['#'], // Just a hash
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function invalidRgbStringData(): array
    {
        return [
            ['255:100'], // Missing blue
            ['-10:20:30'], // Negative values
            ['255:255:256'], // Value out of range
            ['255:255:255:1'], // Too many values
            ['abc:def:ghi'], // Not numbers
        ];
    }

    /**
     * @return array<array<int>>
     */
    public function invalidRgbComponentsData(): array
    {
        return [
            [-1, 0, 0], // Negative red
            [0, -1, 0], // Negative green
            [0, 0, -1], // Negative blue
            [256, 0, 0], // Red too high
            [0, 256, 0], // Green too high
            [0, 0, 256], // Blue too high
        ];
    }
}
