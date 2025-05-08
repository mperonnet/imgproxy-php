<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class Contrast extends AbstractOption
{
    private float $contrast;

    /**
     * @param float $contrast Contrast adjustment (0 to positive, 1 = unchanged)
     */
    public function __construct(float $contrast)
    {
        if ($contrast <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid contrast: %s (should be greater than 0)', $contrast));
        }

        $this->contrast = $contrast;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'co';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->contrast,
        ];
    }
}