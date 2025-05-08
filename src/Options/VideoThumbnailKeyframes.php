<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

final class VideoThumbnailKeyframes extends AbstractOption
{
    private bool $keyframes;

    /**
     * @param bool $keyframes Whether to use only keyframes for video thumbnails
     */
    public function __construct(bool $keyframes = true)
    {
        $this->keyframes = $keyframes;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'vtk';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->formatBoolean($this->keyframes),
        ];
    }
}