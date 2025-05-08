<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Width extends AbstractOption
{
    private int $width;

    public function __construct(int $width)
    {
        if ($width < 0) {
            throw new InvalidArgumentException(sprintf('Invalid width: %s', $width));
        }

        $this->width = $width;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'w';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->width,
        ];
    }
}
