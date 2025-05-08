<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Padding extends AbstractOption
{
    private ?int $top;
    private ?int $right;
    private ?int $bottom;
    private ?int $left;

    public function __construct(?int $top = null, ?int $right = null, ?int $bottom = null, ?int $left = null)
    {
        if (is_null($top ?? $right ?? $bottom ?? $left)) {
            throw new InvalidArgumentException('At least one dimension must be set');
        }

        $this->top = $top;
        $this->right = $right;
        $this->bottom = $bottom;
        $this->left = $left;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'pd';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->top,
            $this->right,
            $this->bottom,
            $this->left,
        ];
    }
}
