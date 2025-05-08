<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class StripColorProfile extends AbstractOption
{
    private bool $strip;

    public function __construct(bool $strip = true)
    {
        $this->strip = $strip;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'scp';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            (int) $this->strip,
        ];
    }
}
