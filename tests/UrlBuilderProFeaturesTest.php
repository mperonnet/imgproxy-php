<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy;

use Mperonnet\ImgProxy\Options\BlurDetections;
use Mperonnet\ImgProxy\Options\DrawDetections;
use Mperonnet\ImgProxy\Options\InfoOptions;
use Mperonnet\ImgProxy\Options\Resize;
use Mperonnet\ImgProxy\Options\ResizingType;
use Mperonnet\ImgProxy\Options\Blur;
use Mperonnet\ImgProxy\Options\WatermarkText;
use Mperonnet\ImgProxy\Options\WatermarkUrl;
use PHPUnit\Framework\TestCase;

class UrlBuilderProFeaturesTest extends TestCase
{
    private UrlBuilder $builder;
    private string $key = '252c83c3d4f25cbd5683498becd78d3c252c83c3d4f25cbd5683498becd78d3c';
    private string $salt = '252c83c3d4f25cbd5683498becd78d3c252c83c3d4f25cbd5683498becd78d3c';
    private string $testImageUrl = 'https://example.com/image.jpg';

    protected function setUp(): void
    {
        $this->builder = UrlBuilder::signed($this->key, $this->salt);
    }

    public function testEncryptedUrl(): void
    {
        $builder = $this->builder->useEncryption($this->key);
        $url = $builder->url($this->testImageUrl);

        $this->assertStringContainsString('/enc/', $url);
        // The encrypted URL will be different each time due to random IV, so we can't test the exact string
        $this->assertMatchesRegularExpression('|/enc/[A-Za-z0-9\-_]+|', $url);
    }

    public function testInfoEndpoint(): void
    {
        // Add info options directly to the options chain
        $url = $this->builder
            ->info()
            ->with(InfoOptions::basic())
            ->url($this->testImageUrl);

        // The result should include /info/ path
        $this->assertStringContainsString('/info/', $url);
        $this->assertStringContainsString('/size:1/format:1/dimensions:1/exif:1/iptc:1/xmp:1/video_meta:1/', $url);
    }

    public function testInfoEndpointWithCustomOptions(): void
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
            true     // pagesNumber
        );

        $url = $this->builder
            ->info()
            ->with($options)
            ->url($this->testImageUrl);

        $this->assertStringContainsString('/info/', $url);
        $this->assertStringContainsString('/size:1/format:1/dimensions:1/detect_objects:1/pages_number:1/', $url);
    }

    public function testBlurDetections(): void
    {
        $url = $this->builder
            ->with(new BlurDetections(5.0, ['face']))
            ->url($this->testImageUrl);

        $this->assertStringContainsString('/bd:5:face/', $url);
    }

    public function testDrawDetections(): void
    {
        $url = $this->builder
            ->with(new DrawDetections(true, ['face', 'person']))
            ->url($this->testImageUrl);

        $this->assertStringContainsString('/dd:1:face:person/', $url);
    }

    public function testWatermarkUrl(): void
    {
        $watermarkUrl = 'https://example.com/watermark.png';
        $url = $this->builder
            ->with(new WatermarkUrl($watermarkUrl))
            ->url($this->testImageUrl);

        $expectedEncodedUrl = rtrim(strtr(base64_encode($watermarkUrl), '+/', '-_'), '=');
        $this->assertStringContainsString('/wmu:' . $expectedEncodedUrl . '/', $url);
    }

    public function testWatermarkText(): void
    {
        $watermarkText = 'Copyright 2023';
        $url = $this->builder
            ->with(new WatermarkText($watermarkText))
            ->url($this->testImageUrl);

        $expectedEncodedText = rtrim(strtr(base64_encode($watermarkText), '+/', '-_'), '=');
        $this->assertStringContainsString('/wmt:' . $expectedEncodedText . '/', $url);
    }

    public function testChainedPipeline(): void
    {
        $url = $this->builder
            // Pipeline 1
            ->pipeline(
                new Resize(ResizingType::FIT, 100, 100),
                new Blur(5)
            )
            // Pipeline 2
            ->pipeline(
                new DrawDetections(true, ['face']),
                new WatermarkText('© 2023')
            )
            ->url($this->testImageUrl);

        $this->assertStringContainsString('/rs:fit:100:100/bl:5/-/', $url);

        $expectedTextEncoded = rtrim(strtr(base64_encode('© 2023'), '+/', '-_'), '=');
        $this->assertStringContainsString('/dd:1:face/wmt:' . $expectedTextEncoded . '/', $url);
    }
}
