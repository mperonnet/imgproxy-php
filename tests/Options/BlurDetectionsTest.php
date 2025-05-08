<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BlurDetectionsTest extends TestCase
{
    public function testCreateWithValidSigma(): void
    {
        $option = new BlurDetections(5.0);
        $this->assertSame('bd:5', (string) $option);
    }

    public function testCreateWithClasses(): void
    {
        $option = new BlurDetections(3.5, ['face', 'cat']);
        $this->assertSame('bd:3.5:face:cat', (string) $option);
    }

    public function testCreateWithZeroSigma(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid blur sigma: 0');

        new BlurDetections(0);
    }

    public function testCreateWithNegativeSigma(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid blur sigma: -2.5');

        new BlurDetections(-2.5);
    }
}
