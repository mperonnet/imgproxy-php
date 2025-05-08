<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;
use Onliner\ImgProxy\Support\Color;

final class Monochrome extends AbstractOption
{
    private float $intensity;
    private ?string $color;

    /**
     * @param float $intensity Intensity of the monochrome effect (0-1)
     * @param string|null $color Hex color for the monochrome palette (without leading #)
     */
    public function __construct(float $intensity, ?string $color = null)
    {
        if ($intensity < 0 || $intensity > 1) {
            throw new InvalidArgumentException(sprintf('Invalid monochrome intensity: %s (should be between 0 and 1)', $intensity));
        }

        $this->intensity = $intensity;
        
        if ($color !== null) {
            // Validate the color (will throw InvalidArgumentException if invalid)
            Color::fromHex($color);
        }
        
        $this->color = $color;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'mc';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->intensity,
            $this->color,
        ];
    }
}