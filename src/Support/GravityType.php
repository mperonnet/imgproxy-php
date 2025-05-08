<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Support;

use InvalidArgumentException;

class GravityType
{
    // Basic gravity types
    public const NORTH = 'no';
    public const SOUTH = 'so';
    public const EAST = 'ea';
    public const WEST = 'we';
    public const NORTH_EAST = 'noea';
    public const NORTH_WEST = 'nowe';
    public const SOUTH_EAST = 'soea';
    public const SOUTH_WEST = 'sowe';
    public const CENTER = 'ce';

    // Special gravity types
    public const SMART = 'sm';
    public const FOCUS_POINT = 'fp';

    // Pro gravity types
    public const OBJECT = 'obj';
    public const OBJECT_WEIGHTED = 'objw';

    public const TYPES = [
        self::NORTH,
        self::SOUTH,
        self::EAST,
        self::WEST,
        self::NORTH_EAST,
        self::NORTH_WEST,
        self::SOUTH_EAST,
        self::SOUTH_WEST,
        self::CENTER,
        self::SMART,
        self::FOCUS_POINT,
        self::OBJECT,
        self::OBJECT_WEIGHTED,
    ];

    private string $type;
    private float $xOffset = 0.0;
    private float $yOffset = 0.0;
    /**
     * @var array<string>
     */
    private array $objectClasses = [];
    /**
     * @var array<string, float|int>
     */
    private array $objectWeights = [];

    /**
     * @param string $type Gravity type (no, so, ea, we, noea, nowe, soea, sowe, ce, sm, fp, obj, objw)
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     * @param array<string> $objectClasses Object classes for obj/objw gravity types (e.g., ['face', 'cat'])
     * @param array<string, float|int> $objectWeights Weights for object classes when using objw (e.g., ['face' => 2, 'cat' => 1])
     */
    public function __construct(
        string $type,
        $xOffset = null,
        $yOffset = null,
        array $objectClasses = [],
        array $objectWeights = []
    ) {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException(sprintf('Invalid gravity type: %s', $type));
        }

        $this->type = $type;

        if ($xOffset !== null) {
            $this->xOffset = (float) $xOffset;
        }

        if ($yOffset !== null) {
            $this->yOffset = (float) $yOffset;
        }

        if (in_array($type, [self::OBJECT, self::OBJECT_WEIGHTED])) {
            $this->objectClasses = $objectClasses;

            if ($type === self::OBJECT_WEIGHTED) {
                $this->objectWeights = $objectWeights;
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return self
     */
    public static function __set_state(array $data): self
    {
        return new self(
            $data['type'] ?? '',
            $data['xOffset'] ?? 0,
            $data['yOffset'] ?? 0,
            $data['objectClasses'] ?? [],
            $data['objectWeights'] ?? []
        );
    }

    /**
     * Returns the gravity value to be used in the URL.
     *
     * @return string
     */
    public function value(): string
    {
        if ($this->type === self::SMART) {
            return $this->type;
        }

        if ($this->type === self::FOCUS_POINT) {
            return sprintf('%s:%s:%s', $this->type, $this->xOffset, $this->yOffset);
        }

        if ($this->type === self::OBJECT) {
            $result = $this->type;
            if (!empty($this->objectClasses)) {
                $result .= ':' . implode(':', $this->objectClasses);
            }

            return $result;
        }

        if ($this->type === self::OBJECT_WEIGHTED) {
            if (empty($this->objectWeights) && empty($this->objectClasses)) {
                return $this->type;
            }

            $result = [$this->type];

            // If we have classes but no weights, treat them as weight 1
            if (empty($this->objectWeights) && !empty($this->objectClasses)) {
                foreach ($this->objectClasses as $class) {
                    $result[] = $class;
                    $result[] = '1';
                }

                return implode(':', $result);
            }

            // Add classes with weights
            foreach ($this->objectWeights as $class => $weight) {
                $result[] = $class;
                $result[] = (string) $weight;
            }

            return implode(':', $result);
        }

        if ($this->xOffset === 0.0 && $this->yOffset === 0.0) {
            return $this->type;
        }

        return sprintf('%s:%s:%s', $this->type, $this->xOffset, $this->yOffset);
    }

    /**
     * Creates a north gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function north($xOffset = null, $yOffset = null): self
    {
        return new self(self::NORTH, $xOffset, $yOffset);
    }

    /**
     * Creates a south gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function south($xOffset = null, $yOffset = null): self
    {
        return new self(self::SOUTH, $xOffset, $yOffset);
    }

    /**
     * Creates an east gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function east($xOffset = null, $yOffset = null): self
    {
        return new self(self::EAST, $xOffset, $yOffset);
    }

    /**
     * Creates a west gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function west($xOffset = null, $yOffset = null): self
    {
        return new self(self::WEST, $xOffset, $yOffset);
    }

    /**
     * Creates a northeast gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function northEast($xOffset = null, $yOffset = null): self
    {
        return new self(self::NORTH_EAST, $xOffset, $yOffset);
    }

    /**
     * Creates a northwest gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function northWest($xOffset = null, $yOffset = null): self
    {
        return new self(self::NORTH_WEST, $xOffset, $yOffset);
    }

    /**
     * Creates a southeast gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function southEast($xOffset = null, $yOffset = null): self
    {
        return new self(self::SOUTH_EAST, $xOffset, $yOffset);
    }

    /**
     * Creates a southwest gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function southWest($xOffset = null, $yOffset = null): self
    {
        return new self(self::SOUTH_WEST, $xOffset, $yOffset);
    }

    /**
     * Creates a center gravity.
     *
     * @param float|int|null $xOffset X offset (0-1 for relative, >=1 for absolute)
     * @param float|int|null $yOffset Y offset (0-1 for relative, >=1 for absolute)
     *
     * @return self
     */
    public static function center($xOffset = null, $yOffset = null): self
    {
        return new self(self::CENTER, $xOffset, $yOffset);
    }

    /**
     * Creates a smart gravity (using image content detection).
     *
     * @return self
     */
    public static function smart(): self
    {
        return new self(self::SMART);
    }

    /**
     * Creates a focus point gravity.
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
     * Creates an object-detection based gravity (Pro feature).
     *
     * @param array<string> $objectClasses Object classes to detect (e.g., ['face', 'cat'])
     *
     * @return self
     */
    public static function object(array $objectClasses = []): self
    {
        return new self(self::OBJECT, null, null, $objectClasses);
    }

    /**
     * Creates a weighted object-detection based gravity (Pro feature).
     *
     * @param array<string, float|int> $objectWeights Object classes with weights (e.g., ['face' => 2, 'cat' => 1])
     *
     * @return self
     */
    public static function objectWeighted(array $objectWeights = []): self
    {
        return new self(self::OBJECT_WEIGHTED, null, null, [], $objectWeights);
    }
}
