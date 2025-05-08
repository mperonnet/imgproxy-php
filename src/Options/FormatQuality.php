<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class FormatQuality extends AbstractOption
{
    /**
     * @var array[]
     */
    private array $options = [];

    /**
     * @param array<string, int> $options
     */
    public function __construct(array $options)
    {
        foreach ($options as $format => $quality) {
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
    
    /**
     * Add a quality setting for a specific format.
     *
     * @param string $format Image format
     * @param int $quality Quality value (0-100)
     *
     * @return self
     */
    public function add(string $format, int $quality): self
    {
        $newOptions = [];
        
        // Copy existing options
        foreach ($this->options as [$fmt, $qual]) {
            $newOptions[$fmt] = $qual;
        }
        
        // Add new option
        $newOptions[$format] = $quality;
        
        return new self($newOptions);
    }
}