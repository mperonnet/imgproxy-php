<?php

declare(strict_types=1);

namespace Onliner\ImgProxy;

use Onliner\ImgProxy\Options\Width;
use Onliner\ImgProxy\Options\Height;
use Onliner\ImgProxy\Options\Quality;
use Onliner\ImgProxy\Options\Dpr;
use Onliner\ImgProxy\Options\Resize;
use Onliner\ImgProxy\Options\Gravity;
use Onliner\ImgProxy\Options\Blur;
use Onliner\ImgProxy\Options\Watermark;
use Onliner\ImgProxy\Options\WatermarkText;
use Onliner\ImgProxy\Options\WatermarkUrl;
use Onliner\ImgProxy\Options\BlurDetections;
use Onliner\ImgProxy\Options\DrawDetections;
use Onliner\ImgProxy\Options\InfoOptions;
use Onliner\ImgProxy\Options\Adjust;
use Onliner\ImgProxy\Options\Brightness;
use Onliner\ImgProxy\Options\Contrast;
use Onliner\ImgProxy\Options\Saturation;
use Onliner\ImgProxy\Options\Monochrome;
use Onliner\ImgProxy\Options\Duotone;
use Onliner\ImgProxy\Options\Colorize;
use Onliner\ImgProxy\Options\Gradient;
use Onliner\ImgProxy\Support\Color;
use Onliner\ImgProxy\Options\Format;
use Onliner\ImgProxy\Options\FormatQuality;
use Onliner\ImgProxy\Options\Autoquality;
use Onliner\ImgProxy\Options\MaxBytes;
use PHPUnit\Framework\TestCase;

class UrlBuilderFeatureTest extends TestCase
{
    private UrlBuilder $builder;
    private string $src = 'http://example.com/image.jpg';

    protected function setUp(): void
    {
        // Mock key and salt for demonstration purposes
        $key = '736563726574';
        $salt = '68656C6C6F';
        
        $this->builder = UrlBuilder::signed($key, $salt);
    }

    public function testBasicUrl(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new Quality(90),
            new Dpr(2)
        )->url($this->src);
        
        $this->assertStringContainsString('w:300', $url);
        $this->assertStringContainsString('h:400', $url);
        $this->assertStringContainsString('q:90', $url);
        $this->assertStringContainsString('dpr:2', $url);
    }

    public function testPlainUrlFormat(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400)
        )->usePlain()->url($this->src);
        
        $this->assertStringContainsString('plain/', $url);
        $this->assertStringContainsString('http://example.com', $url);
    }

    public function testChangeFormatToWebP(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400)
        )->url($this->src, 'webp');
        
        $this->assertStringContainsString('.webp', $url);
    }

    public function testSmartGravity(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            Gravity::smart()
        )->url($this->src);
        
        $this->assertStringContainsString('g:sm', $url);
    }

    public function testObjectDetectionGravity(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            Gravity::object(['face', 'cat'])
        )->url($this->src);
        
        $this->assertStringContainsString('g:obj:face:cat', $url);
    }

    public function testWeightedObjectGravity(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            Gravity::objectWeighted(['face' => 2, 'cat' => 1])
        )->url($this->src);
        
        $this->assertStringContainsString('g:objw:face:2:cat:1', $url);
    }

    public function testWatermark(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            Watermark::southEast(0.7)
        )->url($this->src);
        
        $this->assertStringContainsString('wm:0.7:soea', $url);
    }

    public function testWatermarkWithText(): void
    {
        $text = '© Example Corp';
        $encodedText = rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
        
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new WatermarkText($text)
        )->url($this->src);
        
        $this->assertStringContainsString('wmt:' . $encodedText, $url);
    }

    public function testChainedPipelines(): void
    {
        $url = $this->builder
            ->with(new Resize('fit', 300, 300))
            ->pipeline(
                new Blur(10),
                Watermark::southEast(0.7)
            )
            ->url($this->src);
        
        $this->assertStringContainsString('rs:fit:300:300', $url);
        $this->assertStringContainsString('/-/', $url);
        $this->assertStringContainsString('bl:10', $url);
        $this->assertStringContainsString('wm:0.7:soea', $url);
    }

    public function testInfoEndpoint(): void
    {
        $url = $this->builder
            ->info()
            ->with(InfoOptions::basic())
            ->url($this->src);
        
        $this->assertStringContainsString('/info/', $url);
    }

    public function testColorEffects(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new Blur(5),
            new Monochrome(0.8, 'b3b3b3')
        )->url($this->src);
        
        $this->assertStringContainsString('bl:5', $url);
        $this->assertStringContainsString('mc:0.8:b3b3b3', $url);
    }

    public function testImageAdjustments(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new Adjust(10, 1.1, 0.9)
        )->url($this->src);
        
        $this->assertStringContainsString('a:10:1.1:0.9', $url);
    }

    public function testObjectDetection(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new BlurDetections(5, ['face']),
            new DrawDetections(true, ['face', 'cat'])
        )->url($this->src);
        
        $this->assertStringContainsString('bd:5:face', $url);
        $this->assertStringContainsString('dd:1:face:cat', $url);
    }

    public function testAdvancedQualitySettings(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new Format('webp'),
            new FormatQuality([
                'jpeg' => 85,
                'webp' => 80,
                'avif' => 60
            ]),
            Autoquality::dssim(0.02, 70, 85, 0.001)
        )->url($this->src);
        
        $this->assertStringContainsString('f:webp', $url);
        $this->assertStringContainsString('fq:jpeg:85:webp:80:avif:60', $url);
        $this->assertStringContainsString('aq:dssim:0.02:70:85:0.001', $url);
    }

    public function testGradientEffect(): void
    {
        $url = $this->builder->with(
            new Width(300),
            new Height(400),
            new Gradient(0.7, 'ff0000', 45, 0.2, 0.8)
        )->url($this->src);
        
        $this->assertStringContainsString('gr:0.7:ff0000:45:0.2:0.8', $url);
    }
}