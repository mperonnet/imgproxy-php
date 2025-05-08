<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class Page extends AbstractOption
{
    private int $page;

    /**
     * @param int $page Page number (0-based)
     */
    public function __construct(int $page)
    {
        if ($page < 0) {
            throw new InvalidArgumentException(sprintf('Invalid page number: %s (should be greater than or equal to 0)', $page));
        }

        $this->page = $page;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'pg';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->page,
        ];
    }
}