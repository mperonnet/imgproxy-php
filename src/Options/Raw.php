<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class Raw extends AbstractOption
{
    private bool $raw;

    public function __construct(bool $raw = true)
    {
        $this->raw = $raw;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'raw';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            (int) $this->raw,
        ];
    }
}
