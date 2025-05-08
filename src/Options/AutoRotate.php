<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class AutoRotate extends AbstractOption
{
    private bool $rotate;

    public function __construct(bool $rotate = true)
    {
        $this->rotate = $rotate;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'ar';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            (int) $this->rotate,
        ];
    }
}
