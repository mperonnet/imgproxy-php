<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

/**
 * Options for the ImgProxy Pro info endpoint
 * 
 * This class defines options for the info endpoint in ImgProxy Pro, which
 * provides metadata about the source image.
 */
final class InfoOptions extends AbstractOption
{
    private bool $size;
    private bool $format;
    private bool $dimensions;
    private bool $exif;
    private bool $iptc;
    private bool $xmp;
    private bool $videoMeta;
    private bool $detectObjects;
    private bool $colorspace;
    private bool $bands;
    private bool $sampleFormat;
    private bool $pagesNumber;
    private bool $alpha;
    private bool $checkTransparency;
    private bool $palette;
    private int $colors;
    private bool $average;
    private bool $ignoreTransparent;
    private bool $dominantColors;
    private bool $buildMissed;
    private int $blurhashXComponents;
    private int $blurhashYComponents;
    /**
     * @var array<string>
     */
    private array $hashsums;

    /**
     * @param bool $size Whether to include file size information
     * @param bool $format Whether to include format information
     * @param bool $dimensions Whether to include image dimensions
     * @param bool $exif Whether to include EXIF metadata
     * @param bool $iptc Whether to include IPTC metadata
     * @param bool $xmp Whether to include XMP metadata
     * @param bool $videoMeta Whether to include video metadata
     * @param bool $detectObjects Whether to detect objects
     * @param bool $colorspace Whether to include colorspace information
     * @param bool $bands Whether to include number of bands
     * @param bool $sampleFormat Whether to include sample format
     * @param bool $pagesNumber Whether to include the number of pages
     * @param bool $alpha Whether to check for alpha channel
     * @param bool $checkTransparency Whether to check for actual transparency (used with alpha)
     * @param bool $palette Whether to build color palette
     * @param int $colors Number of colors in the palette (2-256)
     * @param bool $average Whether to calculate average color
     * @param bool $ignoreTransparent Whether to ignore transparent pixels when calculating average color
     * @param bool $dominantColors Whether to calculate dominant colors
     * @param bool $buildMissed Whether to build colors that weren't found
     * @param int $blurhashXComponents X components for BlurHash (1-9)
     * @param int $blurhashYComponents Y components for BlurHash (1-9)
     * @param array<string> $hashsums Hashsum types to calculate (md5, sha1, sha256, sha512)
     */
    public function __construct(
        bool $size = true,
        bool $format = true,
        bool $dimensions = true,
        bool $exif = true,
        bool $iptc = true,
        bool $xmp = true,
        bool $videoMeta = true,
        bool $detectObjects = false,
        bool $colorspace = false,
        bool $bands = false,
        bool $sampleFormat = false,
        bool $pagesNumber = false,
        bool $alpha = false,
        bool $checkTransparency = false,
        bool $palette = false,
        int $colors = 0,
        bool $average = false,
        bool $ignoreTransparent = true,
        bool $dominantColors = false,
        bool $buildMissed = false,
        int $blurhashXComponents = 0,
        int $blurhashYComponents = 0,
        array $hashsums = []
    ) {
        $this->size = $size;
        $this->format = $format;
        $this->dimensions = $dimensions;
        $this->exif = $exif;
        $this->iptc = $iptc;
        $this->xmp = $xmp;
        $this->videoMeta = $videoMeta;
        $this->detectObjects = $detectObjects;
        $this->colorspace = $colorspace;
        $this->bands = $bands;
        $this->sampleFormat = $sampleFormat;
        $this->pagesNumber = $pagesNumber;
        $this->alpha = $alpha;
        $this->checkTransparency = $checkTransparency;
        
        if ($colors < 0 || ($colors > 0 && ($colors < 2 || $colors > 256))) {
            throw new \InvalidArgumentException('Colors must be 0 (disabled) or between 2 and 256');
        }
        
        $this->palette = $palette;
        $this->colors = $colors;
        $this->average = $average;
        $this->ignoreTransparent = $ignoreTransparent;
        $this->dominantColors = $dominantColors;
        $this->buildMissed = $buildMissed;
        
        if ($blurhashXComponents < 0 || $blurhashXComponents > 9) {
            throw new \InvalidArgumentException('BlurHash X components must be between 0 and 9');
        }
        
        if ($blurhashYComponents < 0 || $blurhashYComponents > 9) {
            throw new \InvalidArgumentException('BlurHash Y components must be between 0 and 9');
        }
        
        $this->blurhashXComponents = $blurhashXComponents;
        $this->blurhashYComponents = $blurhashYComponents;
        
        $validHashsums = ['md5', 'sha1', 'sha256', 'sha512'];
        foreach ($hashsums as $hashsum) {
            if (!in_array($hashsum, $validHashsums)) {
                throw new \InvalidArgumentException(sprintf('Invalid hashsum type: %s', $hashsum));
            }
        }
        
        $this->hashsums = $hashsums;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [];
    }
    
    /**
     * @inheritDoc
     */
    public function value(): string
    {
        $options = [];
        
        if ($this->size) {
            $options[] = 'size:1';
        }
        
        if ($this->format) {
            $options[] = 'format:1';
        }
        
        if ($this->dimensions) {
            $options[] = 'dimensions:1';
        }
        
        if ($this->exif) {
            $options[] = 'exif:1';
        }
        
        if ($this->iptc) {
            $options[] = 'iptc:1';
        }
        
        if ($this->xmp) {
            $options[] = 'xmp:1';
        }
        
        if ($this->videoMeta) {
            $options[] = 'video_meta:1';
        }
        
        if ($this->detectObjects) {
            $options[] = 'detect_objects:1';
        }
        
        if ($this->colorspace) {
            $options[] = 'colorspace:1';
        }
        
        if ($this->bands) {
            $options[] = 'bands:1';
        }
        
        if ($this->sampleFormat) {
            $options[] = 'sample_format:1';
        }
        
        if ($this->pagesNumber) {
            $options[] = 'pages_number:1';
        }
        
        if ($this->alpha) {
            $options[] = sprintf('alpha:1:%s', $this->checkTransparency ? '1' : '0');
        }
        
        if ($this->palette && $this->colors > 0) {
            $options[] = sprintf('palette:%d', $this->colors);
        }
        
        if ($this->average) {
            $options[] = sprintf('average:1:%s', $this->ignoreTransparent ? '1' : '0');
        }
        
        if ($this->dominantColors) {
            $options[] = sprintf('dominant_colors:1:%s', $this->buildMissed ? '1' : '0');
        }
        
        if ($this->blurhashXComponents > 0 && $this->blurhashYComponents > 0) {
            $options[] = sprintf('blurhash:%d:%d', $this->blurhashXComponents, $this->blurhashYComponents);
        }
        
        if (!empty($this->hashsums)) {
            $options[] = 'calc_hashsums:' . implode(':', $this->hashsums);
        }
        
        return implode('/', $options);
    }
    
    /**
     * Create a basic info options configuration.
     *
     * @return self
     */
    public static function basic(): self
    {
        return new self();
    }
    
    /**
     * Create a complete info options configuration.
     *
     * @return self
     */
    public static function complete(): self
    {
        return new self(
            true, true, true, true, true, true, true,
            true, true, true, true, true, true, true,
            true, 8, true, true, true, true, 4, 4,
            ['md5', 'sha256']
        );
    }
}