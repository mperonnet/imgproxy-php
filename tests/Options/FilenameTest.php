<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use PHPUnit\Framework\TestCase;

class FilenameTest extends TestCase
{
    public function testCreate(): void
    {
        $opt = new Filename('curiosity');
        $this->assertSame('fn:curiosity', (string) $opt);
    }

    public function testCreateFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Filename cannot be empty');

        new Filename('');
    }
}
