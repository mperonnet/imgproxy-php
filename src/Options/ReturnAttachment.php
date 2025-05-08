<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class ReturnAttachment extends AbstractOption
{
    private bool $value;

    public function __construct(bool $value = true)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'att';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            (int) $this->value,
        ];
    }
}
