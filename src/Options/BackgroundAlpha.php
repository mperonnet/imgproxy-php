<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class BackgroundAlpha extends AbstractOption
{
    private float $alpha;

    /**
     * @param float $alpha Alpha value for the background (0-1)
     */
    public function __construct(float $alpha)
    {
        if ($alpha < 0 || $alpha > 1) {
            throw new InvalidArgumentException(sprintf('Invalid alpha: %f (should be between 0 and 1)', $alpha));
        }

        $this->alpha = $alpha;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'bga';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->alpha,
        ];
    }
}