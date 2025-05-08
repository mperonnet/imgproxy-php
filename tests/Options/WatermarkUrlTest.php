<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use PHPUnit\Framework\TestCase;

class WatermarkUrlTest extends TestCase
{
    public function testCreate(): void
    {
        $url = 'https://example.com/watermark.png';
        $option = new WatermarkUrl($url);

        // The URL should be base64-encoded in URL-safe format
        $expectedEncodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
        $this->assertSame('wmu:' . $expectedEncodedUrl, (string) $option);
    }

    public function testCreateWithEmptyUrl(): void
    {
        $option = new WatermarkUrl('');
        $this->assertSame('wmu:', (string) $option);
    }

    public function testCreateWithSpecialCharacters(): void
    {
        $url = 'https://example.com/watermark with spaces.png?param=value&more=data';
        $option = new WatermarkUrl($url);

        $expectedEncodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
        $this->assertSame('wmu:' . $expectedEncodedUrl, (string) $option);
    }
}
