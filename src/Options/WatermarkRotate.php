<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class WatermarkRotate extends AbstractOption
{
    private int $angle;

    /**
     * @param int $angle Angle to rotate the watermark in degrees (clockwise)
     */
    public function __construct(int $angle)
    {
        // Normalize the angle to be within 0-359
        $this->angle = $angle % 360;
        if ($this->angle < 0) {
            $this->angle += 360;
        }
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wmr';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->angle,
        ];
    }
}
