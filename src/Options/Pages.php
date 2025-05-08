<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class Pages extends AbstractOption
{
    private int $pages;

    /**
     * @param int $pages Number of pages to use
     */
    public function __construct(int $pages)
    {
        if ($pages < 1) {
            throw new InvalidArgumentException(sprintf('Invalid pages number: %s (should be greater than 0)', $pages));
        }

        $this->pages = $pages;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'pgs';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->pages,
        ];
    }
}