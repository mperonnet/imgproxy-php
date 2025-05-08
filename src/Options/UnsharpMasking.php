<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class UnsharpMasking extends AbstractOption
{
    private ?string $mode;
    private ?float $weight;
    private ?float $divider;

    /**
     * @param string|null $mode Unsharp mask mode (auto, none, or always)
     * @param float|null $weight Weight of the unsharp mask
     * @param float|null $divider Divider of the unsharp mask
     */
    public function __construct(?string $mode = null, ?float $weight = null, ?float $divider = null)
    {
        if ($mode !== null && !in_array($mode, ['auto', 'none', 'always'])) {
            throw new InvalidArgumentException(sprintf('Invalid mode: %s (should be auto, none, or always)', $mode));
        }

        if ($weight !== null && $weight <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid weight: %f (should be greater than 0)', $weight));
        }

        if ($divider !== null && $divider <= 0) {
            throw new InvalidArgumentException(sprintf('Invalid divider: %f (should be greater than 0)', $divider));
        }

        $this->mode = $mode;
        $this->weight = $weight;
        $this->divider = $divider;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'ush';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->mode,
            $this->weight,
            $this->divider,
        ];
    }
}
