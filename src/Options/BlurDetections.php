<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class BlurDetections extends AbstractOption
{
    private float $sigma;
    /**
     * @var array<string>
     */
    private array $classes;

    /**
     * @param float $sigma Size of the blur mask (higher values = more blur)
     * @param array<string> $classes Object classes to blur (e.g., ['face', 'cat'])
     */
    public function __construct(float $sigma, array $classes = [])
    {
        if ($sigma <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid blur sigma: %s', $sigma));
        }

        $this->sigma = $sigma;
        $this->classes = $classes;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'bd';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $result = [$this->sigma];

        foreach ($this->classes as $class) {
            $result[] = $class;
        }

        return $result;
    }
}
