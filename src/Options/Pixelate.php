<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Pixelate extends AbstractOption
{
    private int $size;

    /**
     * @param int $size Pixel size for the pixelate effect
     */
    public function __construct(int $size)
    {
        if ($size <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid size: %s (should be greater than 0)', $size));
        }

        $this->size = $size;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'pix';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->size,
        ];
    }
}
