<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use PHPUnit\Framework\TestCase;

class WatermarkTextTest extends TestCase
{
    public function testCreate(): void
    {
        $text = 'Copyright 2023';
        $option = new WatermarkText($text);

        // The text should be base64-encoded in URL-safe format
        $expectedEncodedText = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        $this->assertSame('wmt:' . $expectedEncodedText, (string) $option);
    }

    public function testCreateWithEmptyText(): void
    {
        $option = new WatermarkText('');
        $this->assertSame('wmt:', (string) $option);
    }

    public function testCreateWithSpecialCharacters(): void
    {
        $text = 'Copyright © 2023 | All Rights Reserved 🔒';
        $option = new WatermarkText($text);

        $expectedEncodedText = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        $this->assertSame('wmt:' . $expectedEncodedText, (string) $option);
    }

    public function testCreateWithPangoMarkup(): void
    {
        $text = '<span font="Arial" size="large" foreground="white">WATERMARK</span>';
        $option = new WatermarkText($text);

        $expectedEncodedText = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        $this->assertSame('wmt:' . $expectedEncodedText, (string) $option);
    }
}
