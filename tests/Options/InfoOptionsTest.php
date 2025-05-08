<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use PHPUnit\Framework\TestCase;

class InfoOptionsTest extends TestCase
{
    public function testDefaultOptionsValue(): void
    {
        $options = new InfoOptions();

        // Default options should enable basic metadata (size, format, dimensions, exif, iptc, xmp)
        $expected = 'size:1/format:1/dimensions:1/exif:1/iptc:1/xmp:1/video_meta:1';
        $this->assertSame($expected, $options->value());
    }

    public function testBasicMethod(): void
    {
        $options = InfoOptions::basic();

        // Basic options should be the same as default constructor
        $expected = 'size:1/format:1/dimensions:1/exif:1/iptc:1/xmp:1/video_meta:1';
        $this->assertSame($expected, $options->value());
    }

    public function testCompleteMethod(): void
    {
        $options = InfoOptions::complete();

        // Complete should enable all options
        $expected = 'size:1/format:1/dimensions:1/exif:1/iptc:1/xmp:1/video_meta:1/detect_objects:1/colorspace:1/' .
                   'bands:1/sample_format:1/pages_number:1/alpha:1:1/palette:8/average:1:1/dominant_colors:1:1/' .
                   'blurhash:4:4/calc_hashsums:md5:sha256';
        $this->assertSame($expected, $options->value());
    }

    public function testCustomOptions(): void
    {
        $options = new InfoOptions(
            true,    // size
            true,    // format
            true,    // dimensions
            false,   // exif
            false,   // iptc
            false,   // xmp
            false,   // videoMeta
            true,    // detectObjects
            false,   // colorspace
            false,   // bands
            false,   // sampleFormat
            true,    // pagesNumber
            true,    // alpha
            false,   // checkTransparency
            true,    // palette
            5,       // colors
            true,    // average
            false,   // ignoreTransparent
            false,   // dominantColors
            false,   // buildMissed
            3,       // blurhashXComponents
            4,       // blurhashYComponents
            ['md5']  // hashsums
        );

        $expected = 'size:1/format:1/dimensions:1/detect_objects:1/pages_number:1/alpha:1:0/palette:5/average:1:0/blurhash:3:4/calc_hashsums:md5';
        $this->assertSame($expected, $options->value());
    }

    public function testInvalidColors(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Colors must be 0 (disabled) or between 2 and 256');

        new InfoOptions(
            true, true, true, true, true, true, true,
            false, false, false, false, false, false, false,
            true, 257, // Invalid colors value (> 256)
            false, true, false, false, 0, 0, []
        );
    }

    public function testInvalidBlurhashXComponents(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('BlurHash X components must be between 0 and 9');

        new InfoOptions(
            true, true, true, true, true, true, true,
            false, false, false, false, false, false, false,
            false, 0, false, true, false, false,
            10, // Invalid X components (> 9)
            5, []
        );
    }

    public function testInvalidBlurhashYComponents(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('BlurHash Y components must be between 0 and 9');

        new InfoOptions(
            true, true, true, true, true, true, true,
            false, false, false, false, false, false, false,
            false, 0, false, true, false, false, 5,
            -1, // Invalid Y components (< 0)
            []
        );
    }

    public function testInvalidHashsumType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid hashsum type: invalid');

        new InfoOptions(
            true, true, true, true, true, true, true,
            false, false, false, false, false, false, false,
            false, 0, false, true, false, false, 0, 0,
            ['md5', 'invalid'] // Invalid hashsum type
        );
    }
}
