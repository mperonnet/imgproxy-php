<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Brightness extends AbstractOption
{
    private float $brightness;

    /**
     * @param float $brightness Brightness adjustment (-255 to 255)
     */
    public function __construct(float $brightness)
    {
        if ($brightness < -255 || $brightness > 255) {
            throw new InvalidArgumentException(sprintf('Invalid brightness: %s (should be between -255 and 255)', $brightness));
        }

        $this->brightness = $brightness;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'br';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->brightness,
        ];
    }
}
