<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;
use Onliner\ImgProxy\Support\GravityType;

final class Watermark extends AbstractOption
{
    // Standard positions
    private const REPLICATE_POSITION = 're';
    // Pro positions
    private const CHESSBOARD_POSITION = 'ch';

    private float $opacity;
    private ?string $position;
    private ?float $x;
    private ?float $y;
    private ?float $scale;

    /**
     * @param float $opacity Watermark opacity modifier (0-1)
     * @param string|null $position Position of the watermark
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark relative to the resulting image (0 = no change)
     */
    public function __construct(
        float $opacity,
        ?string $position = null,
        ?float $x = null,
        ?float $y = null,
        ?float $scale = null
    ) {
        if ($opacity < 0 || $opacity > 1) {
            throw new InvalidArgumentException(sprintf('Invalid watermark opacity: %s', $opacity));
        }

        if ($scale !== null && $scale < 0) {
            throw new InvalidArgumentException(sprintf('Invalid watermark scale: %s', $scale));
        }

        if ($position !== null && !in_array($position, $this->positions())) {
            throw new InvalidArgumentException(sprintf('Invalid watermark position: %s', $position));
        }

        $this->opacity = $opacity;
        $this->position = $position;
        $this->x = $x;
        $this->y = $y;
        $this->scale = $scale;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wm';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $data = [$this->opacity];
        
        if ($this->position !== null) {
            $data[] = $this->position;
            
            if ($this->x !== null) {
                $data[] = $this->x;
                
                if ($this->y !== null) {
                    $data[] = $this->y;
                }
            }
        }
        
        if ($this->scale !== null) {
            // Ensure all intermediary values are set if only scale is provided
            if (count($data) === 1) {
                $data[] = null; // position
            }
            if (count($data) === 2) {
                $data[] = null; // x
            }
            if (count($data) === 3) {
                $data[] = null; // y
            }
            
            $data[] = $this->scale;
        }
        
        return $data;
    }

    /**
     * @return array<string>
     */
    private function positions(): array
    {
        return array_merge(
            // Standard gravity positions
            [
                GravityType::NORTH,
                GravityType::SOUTH,
                GravityType::EAST,
                GravityType::WEST,
                GravityType::NORTH_EAST,
                GravityType::NORTH_WEST,
                GravityType::SOUTH_EAST,
                GravityType::SOUTH_WEST,
                GravityType::CENTER,
                GravityType::SMART,
                GravityType::FOCUS_POINT,
            ],
            // Watermark specific positions
            [
                self::REPLICATE_POSITION,
                self::CHESSBOARD_POSITION
            ]
        );
    }
    
    /**
     * Create a watermark in the center position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function center(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::CENTER, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the north (top) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function north(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::NORTH, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the south (bottom) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function south(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::SOUTH, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the east (right) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function east(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::EAST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the west (left) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function west(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::WEST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the north-east (top-right) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function northEast(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::NORTH_EAST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the north-west (top-left) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function northWest(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::NORTH_WEST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the south-east (bottom-right) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function southEast(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::SOUTH_EAST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark in the south-west (bottom-left) position.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function southWest(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, GravityType::SOUTH_WEST, $x, $y, $scale);
    }
    
    /**
     * Create a watermark using smart gravity (content-aware positioning).
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function smart(float $opacity, ?float $scale = null): self
    {
        return new self($opacity, GravityType::SMART, null, null, $scale);
    }
    
    /**
     * Create a watermark at a focus point.
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float $x X coordinate (0-1)
     * @param float $y Y coordinate (0-1)
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function focusPoint(float $opacity, float $x, float $y, ?float $scale = null): self
    {
        return new self($opacity, GravityType::FOCUS_POINT, $x, $y, $scale);
    }
    
    /**
     * Create a replicated watermark (tiled across the entire image).
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x Horizontal spacing between watermarks
     * @param float|null $y Vertical spacing between watermarks
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function replicate(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, self::REPLICATE_POSITION, $x, $y, $scale);
    }
    
    /**
     * Create a chessboard pattern watermark (Pro feature).
     *
     * @param float $opacity Watermark opacity (0-1)
     * @param float|null $x Horizontal spacing between watermarks
     * @param float|null $y Vertical spacing between watermarks
     * @param float|null $scale Scale of the watermark (0 = no change)
     *
     * @return self
     */
    public static function chessboard(float $opacity, ?float $x = null, ?float $y = null, ?float $scale = null): self
    {
        return new self($opacity, self::CHESSBOARD_POSITION, $x, $y, $scale);
    }
}