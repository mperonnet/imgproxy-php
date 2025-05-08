<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class VideoThumbnailSecond extends AbstractOption
{
    private float $second;

    /**
     * @param float $second Second position for video thumbnail extraction
     */
    public function __construct(float $second)
    {
        if ($second < 0) {
            throw new InvalidArgumentException(sprintf('Invalid second: %s (should be greater than or equal to 0)', $second));
        }

        $this->second = $second;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'vts';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->second,
        ];
    }
}
