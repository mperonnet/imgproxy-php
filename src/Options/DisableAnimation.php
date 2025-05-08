<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class DisableAnimation extends AbstractOption
{
    private bool $disable;

    /**
     * @param bool $disable Whether to disable animation
     */
    public function __construct(bool $disable = true)
    {
        $this->disable = $disable;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'da';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->formatBoolean($this->disable),
        ];
    }
}
