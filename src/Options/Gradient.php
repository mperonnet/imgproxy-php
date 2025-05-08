<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;
use Onliner\ImgProxy\Support\Color;

final class Gradient extends AbstractOption
{
    // Direction types
    public const DIRECTION_DOWN = 'down';
    public const DIRECTION_UP = 'up';
    public const DIRECTION_RIGHT = 'right';
    public const DIRECTION_LEFT = 'left';
    
    private float $opacity;
    private ?string $color;
    private ?string $direction;
    private ?float $start;
    private ?float $stop;

    /**
     * @param float $opacity Opacity of the gradient overlay (0-1)
     * @param string|null $color Hex color for gradient (without leading #)
     * @param string|int|null $direction Direction of the gradient ('down', 'up', 'right', 'left' or angle in degrees)
     * @param float|null $start Start position of the gradient (0-1)
     * @param float|null $stop Stop position of the gradient (0-1)
     */
    public function __construct(
        float $opacity,
        ?string $color = null,
        $direction = null,
        ?float $start = null,
        ?float $stop = null
    ) {
        if ($opacity < 0 || $opacity > 1) {
            throw new InvalidArgumentException(sprintf('Invalid gradient opacity: %s (should be between 0 and 1)', $opacity));
        }

        $this->opacity = $opacity;
        
        if ($color !== null) {
            // Validate the color (will throw InvalidArgumentException if invalid)
            Color::fromHex($color);
            $this->color = $color;
        }
        
        // Validate direction
        if ($direction !== null) {
            if (is_string($direction) && !in_array($direction, $this->getDirectionTypes())) {
                throw new InvalidArgumentException(sprintf('Invalid gradient direction: %s', $direction));
            }
            
            if (is_int($direction) && ($direction < 0 || $direction > 359)) {
                throw new InvalidArgumentException(sprintf('Invalid gradient angle: %s (should be between 0 and 359)', $direction));
            }
            
            $this->direction = (string) $direction;
        }
        
        if ($start !== null && ($start < 0 || $start > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid gradient start: %s (should be between 0 and 1)', $start));
        }
        
        if ($stop !== null && ($stop < 0 || $stop > 1)) {
            throw new InvalidArgumentException(sprintf('Invalid gradient stop: %s (should be between 0 and 1)', $stop));
        }
        
        $this->start = $start;
        $this->stop = $stop;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'gr';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->opacity,
            $this->color,
            $this->direction,
            $this->start,
            $this->stop,
        ];
    }
    
    /**
     * @return array<string>
     */
    private function getDirectionTypes(): array
    {
        return [
            self::DIRECTION_DOWN,
            self::DIRECTION_UP,
            self::DIRECTION_RIGHT,
            self::DIRECTION_LEFT,
        ];
    }
}