<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class WebpOptions extends AbstractOption
{
    private ?string $compression;
    private ?bool $smartSubsample;

    /**
     * @param string|null $compression WebP compression method (lossy, near_lossless, lossless)
     * @param bool|null $smartSubsample Whether to use smart subsampling
     */
    public function __construct(?string $compression = null, ?bool $smartSubsample = null)
    {
        if ($compression !== null && !in_array($compression, ['lossy', 'near_lossless', 'lossless'])) {
            throw new \InvalidArgumentException(
                sprintf('Invalid compression: %s (should be lossy, near_lossless, or lossless)', $compression)
            );
        }

        $this->compression = $compression;
        $this->smartSubsample = $smartSubsample;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'webpo';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->compression,
            $this->smartSubsample,
        ];
    }
}
