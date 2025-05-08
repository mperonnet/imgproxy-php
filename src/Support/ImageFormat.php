<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Support;

use InvalidArgumentException;

class ImageFormat
{
    // Standard formats
    public const PNG = 'png';
    public const JPG = 'jpg';
    public const JPEG = 'jpeg';
    public const WEBP = 'webp';
    public const AVIF = 'avif';
    public const GIF = 'gif';
    public const ICO = 'ico';
    public const SVG = 'svg';
    public const HEIC = 'heic';
    public const BMP = 'bmp';
    public const TIFF = 'tiff';
    
    // Document formats
    public const PDF = 'pdf';
    
    // Video formats
    public const MP4 = 'mp4';
    
    // Pro formats
    public const JXL = 'jxl';        // JPEG XL
    public const BEST = 'best';      // Best format
    
    private const SUPPORTED = [
        self::PNG,
        self::JPG,
        self::JPEG,
        self::WEBP,
        self::AVIF,
        self::GIF,
        self::ICO,
        self::SVG,
        self::HEIC,
        self::BMP,
        self::TIFF,
        self::PDF,
        self::MP4,
        self::JXL,
        self::BEST,
    ];

    private string $extension;

    /**
     * @param string $extension
     */
    public function __construct(string $extension)
    {
        $this->extension = $this->cast($extension);

        if (!self::isSupported($this->extension)) {
            throw new InvalidArgumentException(sprintf('Invalid image format: %s', $extension));
        }
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return self
     */
    public static function __set_state(array $data): self
    {
        return new self(...$data);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isSupported(string $value): bool
    {
        return in_array($value, self::SUPPORTED);
    }

    /**
     * @param string $extension
     *
     * @return bool
     */
    public function isEquals(string $extension): bool
    {
        return $this->extension === $this->cast($extension);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * 
     * @return string
     */
    private function cast(string $extension): string
    {
        return strtolower(trim($extension));
    }
    
    /**
     * Create a PNG format.
     * 
     * @return self
     */
    public static function png(): self
    {
        return new self(self::PNG);
    }
    
    /**
     * Create a JPG format.
     * 
     * @return self
     */
    public static function jpg(): self
    {
        return new self(self::JPG);
    }
    
    /**
     * Create a WEBP format.
     * 
     * @return self
     */
    public static function webp(): self
    {
        return new self(self::WEBP);
    }
    
    /**
     * Create an AVIF format.
     * 
     * @return self
     */
    public static function avif(): self
    {
        return new self(self::AVIF);
    }
    
    /**
     * Create a GIF format.
     * 
     * @return self
     */
    public static function gif(): self
    {
        return new self(self::GIF);
    }
    
    /**
     * Create a JPEG XL format (Pro feature).
     * 
     * @return self
     */
    public static function jxl(): self
    {
        return new self(self::JXL);
    }
    
    /**
     * Create a 'best' format (Pro feature) - ImgProxy will select the best format.
     * 
     * @return self
     */
    public static function best(): self
    {
        return new self(self::BEST);
    }
}