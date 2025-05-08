<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;
use Onliner\ImgProxy\Support\Color;

final class Colorize extends AbstractOption
{
    private float $opacity;
    private ?string $color;
    private ?bool $keepAlpha;

    /**
     * @param float $opacity Opacity of the color overlay (0-1)
     * @param string|null $color Hex color for overlay (without leading #)
     * @param bool|null $keepAlpha Whether to preserve the alpha channel of the original image
     */
    public function __construct(float $opacity, ?string $color = null, ?bool $keepAlpha = null)
    {
        if ($opacity < 0 || $opacity > 1) {
            throw new InvalidArgumentException(sprintf('Invalid colorize opacity: %s (should be between 0 and 1)', $opacity));
        }

        $this->opacity = $opacity;
        
        if ($color !== null) {
            // Validate the color (will throw InvalidArgumentException if invalid)
            Color::fromHex($color);
            $this->color = $color;
        }
        
        $this->keepAlpha = $keepAlpha;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'col';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->opacity,
            $this->color,
            $this->keepAlpha,
        ];
    }
}