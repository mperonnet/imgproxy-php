<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

final class JpegOptions extends AbstractOption
{
    private ?bool $progressive;
    private ?bool $noSubsample;
    private ?bool $trellisQuant;
    private ?bool $overshootDeringing;
    private ?bool $optimizeScans;
    private ?int $quantTable;

    /**
     * @param bool|null $progressive Whether to use progressive JPEG format
     * @param bool|null $noSubsample Whether to disable chroma subsampling
     * @param bool|null $trellisQuant Whether to use trellis quantization
     * @param bool|null $overshootDeringing Whether to use overshooting
     * @param bool|null $optimizeScans Whether to optimize scans
     * @param int|null $quantTable Quantization table (0-8)
     */
    public function __construct(
        ?bool $progressive = null,
        ?bool $noSubsample = null,
        ?bool $trellisQuant = null,
        ?bool $overshootDeringing = null,
        ?bool $optimizeScans = null,
        ?int $quantTable = null
    ) {
        if ($quantTable !== null && ($quantTable < 0 || $quantTable > 8)) {
            throw new \InvalidArgumentException(sprintf('Invalid quantization table: %d (should be between 0 and 8)', $quantTable));
        }

        $this->progressive = $progressive;
        $this->noSubsample = $noSubsample;
        $this->trellisQuant = $trellisQuant;
        $this->overshootDeringing = $overshootDeringing;
        $this->optimizeScans = $optimizeScans;
        $this->quantTable = $quantTable;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'jpgo';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->progressive,
            $this->noSubsample,
            $this->trellisQuant,
            $this->overshootDeringing,
            $this->optimizeScans,
            $this->quantTable,
        ];
    }
}