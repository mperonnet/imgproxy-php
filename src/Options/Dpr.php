<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Dpr extends AbstractOption
{
    private int $dpr;

    public function __construct(int $dpr)
    {
        if ($dpr <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid dpr: %s', $dpr));
        }

        $this->dpr = $dpr;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'dpr';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->dpr,
        ];
    }
}
