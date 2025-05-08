<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class VideoThumbnailAnimation extends AbstractOption
{
    private float $step;
    private int $delay;
    private int $frames;
    private int $frameWidth;
    private int $frameHeight;
    private ?bool $extendFrame;
    private ?bool $trim;
    private ?bool $fill;
    private ?float $focusX;
    private ?float $focusY;

    /**
     * @param float $step Step between video frames in seconds (negative for auto calculation)
     * @param int $delay Delay between animation frames in milliseconds
     * @param int $frames Number of animation frames
     * @param int $frameWidth Width of each frame
     * @param int $frameHeight Height of each frame
     * @param bool|null $extendFrame Whether to extend frames to the requested size
     * @param bool|null $trim Whether to trim unused frames from the animation
     * @param bool|null $fill Whether to use fill resizing type for frames
     * @param float|null $focusX X coordinate for fill gravity focus point (0-1)
     * @param float|null $focusY Y coordinate for fill gravity focus point (0-1)
     */
    public function __construct(
        float $step,
        int $delay,
        int $frames,
        int $frameWidth,
        int $frameHeight,
        ?bool $extendFrame = null,
        ?bool $trim = null,
        ?bool $fill = null,
        ?float $focusX = null,
        ?float $focusY = null
    ) {
        if ($delay < 0) {
            throw new InvalidArgumentException(sprintf('Invalid delay: %d (should be greater than or equal to 0)', $delay));
        }

        if ($frames < 0) {
            throw new InvalidArgumentException(sprintf('Invalid frames: %d (should be greater than or equal to 0)', $frames));
        }

        if ($frameWidth < 0) {
            throw new InvalidArgumentException(sprintf('Invalid frame width: %d (should be greater than or equal to 0)', $frameWidth));
        }

        if ($frameHeight < 0) {
            throw new InvalidArgumentException(sprintf('Invalid frame height: %d (should be greater than or equal to 0)', $frameHeight));
        }

        if ($focusX !== null && ($focusX < 0 || $focusX > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid focus X: %f (should be between 0 and 1)', $focusX));
        }

        if ($focusY !== null && ($focusY < 0 || $focusY > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid focus Y: %f (should be between 0 and 1)', $focusY));
        }

        $this->step = $step;
        $this->delay = $delay;
        $this->frames = $frames;
        $this->frameWidth = $frameWidth;
        $this->frameHeight = $frameHeight;
        $this->extendFrame = $extendFrame;
        $this->trim = $trim;
        $this->fill = $fill;
        $this->focusX = $focusX;
        $this->focusY = $focusY;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'vta';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->step,
            $this->delay,
            $this->frames,
            $this->frameWidth,
            $this->frameHeight,
            $this->extendFrame,
            $this->trim,
            $this->fill,
            $this->focusX,
            $this->focusY,
        ];
    }
}
