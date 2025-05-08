<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;
use Mperonnet\ImgProxy\Support\GravityType;

final class Gravity extends AbstractOption
{
    private string $type;
    private ?float $x;
    private ?float $y;
    /**
     * @var array<string>
     */
    private array $objectClasses = [];
    /**
     * @var array<string, float|int>
     */
    private array $objectWeights = [];

    /**
     * @param string|GravityType $type Gravity type or GravityType object
     * @param float|null $x X offset (0-1 for relative, >=1 for absolute)
     * @param float|null $y Y offset (0-1 for relative, >=1 for absolute)
     * @param array<string> $objectClasses Object classes for obj/objw gravity types
     * @param array<string, float|int> $objectWeights Weights for object classes when using objw
     */
    public function __construct(
        $type,
        ?float $x = null,
        ?float $y = null,
        array $objectClasses = [],
        array $objectWeights = []
    ) {
        if ($type instanceof GravityType) {
            $this->type = $type->value();

            // If we have a GravityType object we don't need to validate the other parameters
            // since they are validated by the GravityType constructor.
            $this->x = $x;
            $this->y = $y;
            $this->objectClasses = $objectClasses;
            $this->objectWeights = $objectWeights;
            return;
        }

        if (!in_array($type, GravityType::TYPES)) {
            throw new InvalidArgumentException(sprintf('Invalid gravity type: %s', $type));
        }

        $this->type = $type;

        if ($x !== null && $x < 0) {
            throw new InvalidArgumentException(sprintf('Invalid gravity X: %s', $x));
        }

        if ($y !== null && $y < 0) {
            throw new InvalidArgumentException(sprintf('Invalid gravity Y: %s', $y));
        }

        $this->x = $x;
        $this->y = $y;
        $this->objectClasses = $objectClasses;
        $this->objectWeights = $objectWeights;
    }

    /**
     * @param string $gravity
     *
     * @return static
     */
    public static function fromString(string $gravity): self
    {
        $params = explode(':', $gravity);
        $type = array_shift($params);

        if (!in_array($type, GravityType::TYPES)) {
            throw new InvalidArgumentException(sprintf('Invalid gravity type: %s', $type));
        }

        if ($type === GravityType::OBJECT) {
            return new self($type, null, null, $params);
        }

        if ($type === GravityType::OBJECT_WEIGHTED) {
            $objectWeights = [];

            // Parse pairs of class:weight into an associative array
            for ($i = 0; $i < count($params); $i += 2) {
                if (isset($params[$i + 1])) {
                    if (!is_numeric($params[$i + 1])) {
                        throw new InvalidArgumentException(sprintf('Object weight should be numeric: %s', $params[$i + 1]));
                    }
                    $objectWeights[$params[$i]] = (float) $params[$i + 1];
                }
            }

            return new self($type, null, null, [], $objectWeights);
        }

        // Handle standard gravity types with optional offsets
        $x = null;
        $y = null;

        if (isset($params[0])) {
            if (!is_numeric($params[0])) {
                throw new InvalidArgumentException('Gravity X should be numeric');
            }
            $x = (float) $params[0];

            if ($x < 0) {
                throw new InvalidArgumentException(sprintf('Invalid gravity X: %s', $x));
            }
        }

        if (isset($params[1])) {
            if (!is_numeric($params[1])) {
                throw new InvalidArgumentException('Gravity Y should be numeric');
            }
            $y = (float) $params[1];

            if ($y < 0) {
                throw new InvalidArgumentException(sprintf('Invalid gravity Y: %s', $y));
            }
        }

        return new self($type, $x, $y);
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'g';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        // Object oriented gravity
        if ($this->type === GravityType::OBJECT) {
            $result = [$this->type];

            foreach ($this->objectClasses as $class) {
                $result[] = $class;
            }

            return $result;
        }

        // Weighted object oriented gravity
        if ($this->type === GravityType::OBJECT_WEIGHTED) {
            $result = [$this->type];

            foreach ($this->objectWeights as $class => $weight) {
                $result[] = $class;
                $result[] = $weight;
            }

            return $result;
        }

        // Standard gravity with optional offsets
        return [
            $this->type,
            $this->x,
            $this->y,
        ];
    }

    /**
     * Create a north gravity.
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
     * Create a south gravity.
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
     * Create an east gravity.
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
     * Create a west gravity.
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
     * Create a northeast gravity.
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
     * Create a northwest gravity.
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
     * Create a southeast gravity.
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
     * Create a southwest gravity.
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
     * Create a center gravity.
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
     * Create a smart gravity (using image content detection).
     *
     * @return self
     */
    public static function smart(): self
    {
        return new self(GravityType::SMART);
    }

    /**
     * Create a focus point gravity.
     *
     * @param float $x X coordinate (0-1)
     * @param float $y Y coordinate (0-1)
     *
     * @return self
     */
    public static function focusPoint(float $x, float $y): self
    {
        return new self(GravityType::FOCUS_POINT, $x, $y);
    }

    /**
     * Create an object-detection based gravity (Pro feature).
     *
     * @param array<string> $objectClasses Object classes to detect (e.g., ['face', 'cat'])
     *
     * @return self
     */
    public static function object(array $objectClasses = []): self
    {
        return new self(GravityType::OBJECT, null, null, $objectClasses);
    }

    /**
     * Create a weighted object-detection based gravity (Pro feature).
     *
     * @param array<string, float|int> $objectWeights Object classes with weights (e.g., ['face' => 2, 'cat' => 1])
     *
     * @return self
     */
    public static function objectWeighted(array $objectWeights = []): self
    {
        return new self(GravityType::OBJECT_WEIGHTED, null, null, [], $objectWeights);
    }
}
