<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class PngOptions extends AbstractOption
{
    private ?bool $interlaced;
    private ?bool $quantize;
    private ?int $quantizationColors;

    /**
     * @param bool|null $interlaced Whether to use interlaced PNG format
     * @param bool|null $quantize Whether to use quantization
     * @param int|null $quantizationColors Number of colors for quantization (2-256)
     */
    public function __construct(?bool $interlaced = null, ?bool $quantize = null, ?int $quantizationColors = null)
    {
        if ($quantizationColors !== null && ($quantizationColors < 2 || $quantizationColors > 256)) {
            throw new InvalidArgumentException(sprintf('Invalid quantization colors: %d (should be between 2 and 256)', $quantizationColors));
        }

        $this->interlaced = $interlaced;
        $this->quantize = $quantize;
        $this->quantizationColors = $quantizationColors;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'pngo';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->interlaced,
            $this->quantize,
            $this->quantizationColors,
        ];
    }
}
