<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class KeepCopyright extends AbstractOption
{
    private bool $keep;

    public function __construct(bool $keep = true)
    {
        $this->keep = $keep;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'kcr';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            (int) $this->keep,
        ];
    }
}
