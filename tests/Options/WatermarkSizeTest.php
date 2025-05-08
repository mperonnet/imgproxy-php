<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WatermarkSizeTest extends TestCase
{
    public function testCreateEmpty(): void
    {
        $option = new WatermarkSize();
        $this->assertSame('wms', (string) $option);
    }

    public function testCreateWithWidthOnly(): void
    {
        $option = new WatermarkSize(300);
        $this->assertSame('wms:300', (string) $option);
    }

    public function testCreateWithHeightOnly(): void
    {
        $option = new WatermarkSize(null, 200);
        $this->assertSame('wms::200', (string) $option);
    }

    public function testCreateWithBothDimensions(): void
    {
        $option = new WatermarkSize(300, 200);
        $this->assertSame('wms:300:200', (string) $option);
    }

    public function testCreateWithZeroWidth(): void
    {
        $option = new WatermarkSize(0, 200);
        $this->assertSame('wms:0:200', (string) $option);
    }

    public function testCreateWithZeroHeight(): void
    {
        $option = new WatermarkSize(300, 0);
        $this->assertSame('wms:300:0', (string) $option);
    }

    public function testCreateWithNegativeWidth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid watermark width: -300');

        new WatermarkSize(-300, 200);
    }

    public function testCreateWithNegativeHeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid watermark height: -200');

        new WatermarkSize(300, -200);
    }
}
