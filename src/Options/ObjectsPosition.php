<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;
use Mperonnet\ImgProxy\Support\GravityType;

final class ObjectsPosition extends AbstractOption
{
    public const FOCUS_POINT = 'fp';
    public const PROPORTIONAL = 'prop';

    private string $type;
    private ?float $x;
    private ?float $y;
    private bool $isFocusPoint = false;
    private bool $isProportional = false;

    /**
     * @param string $type Position type (no, so, ea, we, noea, nowe, soea, sowe, ce, fp, prop)
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     */
    public function __construct(string $type, ?float $x = null, ?float $y = null)
    {
        if ($type === self::FOCUS_POINT) {
            $this->isFocusPoint = true;
            $this->type = $type;

            if ($x === null || $y === null) {
                throw new InvalidArgumentException('Focus point requires both X and Y coordinates');
            }

            if ($x < 0 || $x > 1 || $y < 0 || $y > 1) {
                throw new InvalidArgumentException('Focus point coordinates must be between 0 and 1');
            }

            $this->x = $x;
            $this->y = $y;
            return;
        }

        if ($type === self::PROPORTIONAL) {
            $this->isProportional = true;
            $this->type = $type;
            $this->x = null;
            $this->y = null;
            return;
        }

        // For standard position types, we use the GravityType constants since they're the same
        if (!in_array($type, GravityType::TYPES) || $type === GravityType::OBJECT || $type === GravityType::OBJECT_WEIGHTED) {
            throw new InvalidArgumentException(sprintf('Invalid position type: %s', $type));
        }

        $this->type = $type;

        if ($x !== null && $x < 0) {
            throw new InvalidArgumentException(sprintf('Invalid position X: %s', $x));
        }

        if ($y !== null && $y < 0) {
            throw new InvalidArgumentException(sprintf('Invalid position Y: %s', $y));
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param string $position
     *
     * @return static
     */
    public static function fromString(string $position): self
    {
        $params = explode(':', $position);
        $type = array_shift($params);

        if ($type === self::FOCUS_POINT) {
            $x = isset($params[0]) && is_numeric($params[0]) ? (float) $params[0] : null;
            $y = isset($params[1]) && is_numeric($params[1]) ? (float) $params[1] : null;

            if ($x === null || $y === null) {
                throw new InvalidArgumentException('Focus point requires both X and Y coordinates');
            }

            return new self($type, $x, $y);
        }

        if ($type === self::PROPORTIONAL) {
            return new self($type);
        }

        // Handle standard position types with optional offsets
        $x = isset($params[0]) && is_numeric($params[0]) ? (float) $params[0] : null;
        $y = isset($params[1]) && is_numeric($params[1]) ? (float) $params[1] : null;

        return new self($type, $x, $y);
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'op';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        if ($this->isProportional) {
            return [$this->type];
        }

        return [
            $this->type,
            $this->x,
            $this->y,
        ];
    }

    /**
     * Create a north position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function north(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::NORTH, $x, $y);
    }

    /**
     * Create a south position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function south(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::SOUTH, $x, $y);
    }

    /**
     * Create an east position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function east(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::EAST, $x, $y);
    }

    /**
     * Create a west position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function west(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::WEST, $x, $y);
    }

    /**
     * Create a northeast position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function northEast(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::NORTH_EAST, $x, $y);
    }

    /**
     * Create a northwest position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function northWest(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::NORTH_WEST, $x, $y);
    }

    /**
     * Create a southeast position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function southEast(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::SOUTH_EAST, $x, $y);
    }

    /**
     * Create a southwest position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function southWest(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::SOUTH_WEST, $x, $y);
    }

    /**
     * Create a center position.
     *
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function center(?float $x = null, ?float $y = null): self
    {
        return new self(GravityType::CENTER, $x, $y);
    }

    /**
     * Create a focus point position.
     *
     * @param float $x X coordinate (0-1)
     * @param float $y Y coordinate (0-1)
     *
     * @return self
     */
    public static function focusPoint(float $x, float $y): self
    {
        return new self(self::FOCUS_POINT, $x, $y);
    }

    /**
     * Create a proportional position (object offsets in the result image are proportional
     * to their offsets in the original image).
     *
     * @return self
     */
    public static function proportional(): self
    {
        return new self(self::PROPORTIONAL);
    }
}
