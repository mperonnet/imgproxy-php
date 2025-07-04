<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use Mperonnet\ImgProxy\Support\Color;

final class Background extends AbstractOption
{
    private Color $color;

    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'bg';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->color->value(),
        ];
    }
}
