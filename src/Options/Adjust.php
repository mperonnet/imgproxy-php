<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class Adjust extends AbstractOption
{
    private ?float $brightness;
    private ?float $contrast;
    private ?float $saturation;

    /**
     * @param float|null $brightness Brightness adjustment (-255 to 255)
     * @param float|null $contrast Contrast adjustment (0 to positive, 1 = unchanged)
     * @param float|null $saturation Saturation adjustment (0 to positive, 1 = unchanged)
     */
    public function __construct(?float $brightness = null, ?float $contrast = null, ?float $saturation = null)
    {
        $this->brightness = $brightness;
        $this->contrast = $contrast;
        $this->saturation = $saturation;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'a';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->brightness,
            $this->contrast,
            $this->saturation,
        ];
    }
}
