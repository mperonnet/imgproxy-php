<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Filename extends AbstractOption
{
    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Filename cannot be empty');
        }

        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'fn';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->name,
        ];
    }
}
