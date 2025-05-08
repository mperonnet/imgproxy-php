<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class WatermarkShadow extends AbstractOption
{
    private float $sigma;

    /**
     * @param float $sigma Size of the shadow mask (higher values = bigger shadow)
     */
    public function __construct(float $sigma)
    {
        if ($sigma <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid watermark shadow sigma: %s', $sigma));
        }

        $this->sigma = $sigma;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wmsh';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->sigma,
        ];
    }
}
