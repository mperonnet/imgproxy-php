<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class WatermarkSize extends AbstractOption
{
    private ?int $width;
    private ?int $height;

    /**
     * @param int|null $width Desired watermark width (0 = auto-calculate based on height)
     * @param int|null $height Desired watermark height (0 = auto-calculate based on width)
     */
    public function __construct(?int $width = null, ?int $height = null)
    {
        if ($width !== null && $width < 0) {
            throw new InvalidArgumentException(sprintf('Invalid watermark width: %s', $width));
        }

        if ($height !== null && $height < 0) {
            throw new InvalidArgumentException(sprintf('Invalid watermark height: %s', $height));
        }

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wms';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->width,
            $this->height,
        ];
    }
}
