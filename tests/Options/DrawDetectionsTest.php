<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use PHPUnit\Framework\TestCase;

class DrawDetectionsTest extends TestCase
{
    public function testCreateWithDefaultValue(): void
    {
        $option = new DrawDetections();
        $this->assertSame('dd:1', (string) $option);
    }

    public function testCreateWithDrawFalse(): void
    {
        $option = new DrawDetections(false);
        $this->assertSame('dd:0', (string) $option);
    }

    public function testCreateWithClasses(): void
    {
        $option = new DrawDetections(true, ['face', 'person']);
        $this->assertSame('dd:1:face:person', (string) $option);
    }

    public function testCreateWithDrawFalseAndClasses(): void
    {
        $option = new DrawDetections(false, ['cat', 'dog']);
        $this->assertSame('dd:0:cat:dog', (string) $option);
    }
}
