<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Format extends AbstractOption
{
    private string $extension;

    public function __construct(string $extension)
    {
        if (empty($extension)) {
            throw new InvalidArgumentException('Format cannot be empty');
        }

        $this->extension = $extension;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'f';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->extension,
        ];
    }
}
