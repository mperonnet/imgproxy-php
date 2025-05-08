<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Dpi extends AbstractOption
{
    private int $dpi;

    /**
     * @param int $dpi DPI value for the image (or 0 to reset to default)
     */
    public function __construct(int $dpi)
    {
        if ($dpi < 0) {
            throw new InvalidArgumentException(sprintf('Invalid DPI: %d (should be greater than or equal to 0)', $dpi));
        }

        $this->dpi = $dpi;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'dpi';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->dpi,
        ];
    }
}
