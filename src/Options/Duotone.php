<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;
use Mperonnet\ImgProxy\Support\Color;

final class Duotone extends AbstractOption
{
    private float $intensity;
    private ?string $color1;
    private ?string $color2;

    /**
     * @param float $intensity Intensity of the duotone effect (0-1)
     * @param string|null $color1 Hex color for dark areas (without leading #)
     * @param string|null $color2 Hex color for light areas (without leading #)
     */
    public function __construct(float $intensity, ?string $color1 = null, ?string $color2 = null)
    {
        if ($intensity < 0 || $intensity > 1) {
            throw new InvalidArgumentException(sprintf('Invalid duotone intensity: %s (should be between 0 and 1)', $intensity));
        }

        $this->intensity = $intensity;

        if ($color1 !== null) {
            // Validate the color (will throw InvalidArgumentException if invalid)
            Color::fromHex($color1);
            $this->color1 = $color1;
        }

        if ($color2 !== null) {
            // Validate the color (will throw InvalidArgumentException if invalid)
            Color::fromHex($color2);
            $this->color2 = $color2;
        }
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'dt';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->intensity,
            $this->color1,
            $this->color2,
        ];
    }
}
