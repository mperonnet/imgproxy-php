<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class FormatQuality extends AbstractOption
{
    /**
     * @var array<array{0: string, 1: int}>
     */
    private array $options = [];

    /**
     * @param array<string, int> $options
     */
    public function __construct(array $options)
    {
        foreach ($options as $format => $quality) {
            if (!is_int($quality)) {
                $quality = (int) $quality;
            }

            if ($quality < 0 || $quality > 100) {
                throw new InvalidArgumentException(sprintf('Invalid quality: %s (should be between 0 and 100)', $quality));
            }

            $this->options[] = [$format, $quality];
        }

        if (empty($this->options)) {
            throw new InvalidArgumentException('At least one format quality must be set');
        }
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'fq';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return array_merge(...$this->options);
    }

}
