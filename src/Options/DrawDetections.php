<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class DrawDetections extends AbstractOption
{
    private bool $draw;
    /**
     * @var array<string>
     */
    private array $classes;

    /**
     * @param bool $draw Whether to draw detection boxes (true = draw)
     * @param array<string> $classes Object classes to draw (e.g., ['face', 'cat'])
     */
    public function __construct(bool $draw = true, array $classes = [])
    {
        $this->draw = $draw;
        $this->classes = $classes;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'dd';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $result = [$this->formatBoolean($this->draw)];

        foreach ($this->classes as $class) {
            $result[] = $class;
        }

        return $result;
    }
}
