<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class Saturation extends AbstractOption
{
    private float $saturation;

    /**
     * @param float $saturation Saturation adjustment (0 to positive, 1 = unchanged)
     */
    public function __construct(float $saturation)
    {
        if ($saturation < 0) {
            throw new InvalidArgumentException(sprintf('Invalid saturation: %s (should be greater than or equal to 0)', $saturation));
        }

        $this->saturation = $saturation;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'sa';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->saturation,
        ];
    }
}