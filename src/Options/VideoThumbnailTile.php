<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class VideoThumbnailTile extends AbstractOption
{
    private float $step;
    private int $columns;
    private int $rows;
    private int $tileWidth;
    private int $tileHeight;
    private ?bool $extendTile;
    private ?bool $trim;
    private ?bool $fill;
    private ?float $focusX;
    private ?float $focusY;

    /**
     * @param float $step Step between video frames in seconds (negative for auto calculation)
     * @param int $columns Number of columns in the sprite
     * @param int $rows Number of rows in the sprite
     * @param int $tileWidth Width of each tile
     * @param int $tileHeight Height of each tile
     * @param bool|null $extendTile Whether to extend tiles to the requested size
     * @param bool|null $trim Whether to trim unused space from the sprite
     * @param bool|null $fill Whether to use fill resizing type for tiles
     * @param float|null $focusX X coordinate for fill gravity focus point (0-1)
     * @param float|null $focusY Y coordinate for fill gravity focus point (0-1)
     */
    public function __construct(
        float $step,
        int $columns,
        int $rows,
        int $tileWidth,
        int $tileHeight,
        ?bool $extendTile = null,
        ?bool $trim = null,
        ?bool $fill = null,
        ?float $focusX = null,
        ?float $focusY = null
    ) {
        if ($columns < 0) {
            throw new InvalidArgumentException(sprintf('Invalid columns: %d (should be greater than or equal to 0)', $columns));
        }

        if ($rows < 0) {
            throw new InvalidArgumentException(sprintf('Invalid rows: %d (should be greater than or equal to 0)', $rows));
        }

        if ($tileWidth < 0) {
            throw new InvalidArgumentException(sprintf('Invalid tile width: %d (should be greater than or equal to 0)', $tileWidth));
        }

        if ($tileHeight < 0) {
            throw new InvalidArgumentException(sprintf('Invalid tile height: %d (should be greater than or equal to 0)', $tileHeight));
        }

        if ($focusX !== null && ($focusX < 0 || $focusX > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid focus X: %f (should be between 0 and 1)', $focusX));
        }

        if ($focusY !== null && ($focusY < 0 || $focusY > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid focus Y: %f (should be between 0 and 1)', $focusY));
        }

        $this->step = $step;
        $this->columns = $columns;
        $this->rows = $rows;
        $this->tileWidth = $tileWidth;
        $this->tileHeight = $tileHeight;
        $this->extendTile = $extendTile;
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
        return 'vtt';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->step,
            $this->columns,
            $this->rows,
            $this->tileWidth,
            $this->tileHeight,
            $this->extendTile,
            $this->trim,
            $this->fill,
            $this->focusX,
            $this->focusY,
        ];
    }
}
