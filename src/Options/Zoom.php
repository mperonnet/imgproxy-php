<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Zoom extends AbstractOption
{
    private float $x;
    private ?float $y;

    public function __construct(float $x, ?float $y = null)
    {
        if ($x <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid zoom X value: %s', $x));
        }

        if ($y !== null && $y <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid zoom Y value: %s', $y));
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'z';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->x,
            $this->y,
        ];
    }
}
