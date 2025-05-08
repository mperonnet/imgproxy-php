<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Support;

use InvalidArgumentException;

class Color
{
    private int $red;
    private int $green;
    private int $blue;
    private ?float $alpha;
    private string $format = 'hex'; // 'hex' or 'rgb'

    /**
     * @param int $red Red component (0-255)
     * @param int $green Green component (0-255)
     * @param int $blue Blue component (0-255)
     * @param float|null $alpha Alpha component (0-1, null for no alpha)
     */
    public function __construct(int $red, int $green, int $blue, ?float $alpha = null)
    {
        $this->validateRgb($red, $green, $blue);
        
        if ($alpha !== null) {
            $this->validateAlpha($alpha);
        }
        
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * Creates a Color from a hex string.
     *
     * @param string $hex Hex color string (e.g. #RRGGBB, RRGGBB, #RGB, RGB)
     * @param float|null $alpha Alpha component (0-1, null for no alpha)
     *
     * @return self
     */
    public static function fromHex(string $hex, ?float $alpha = null): self
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            // Convert shorthand hex to full hex
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            throw new InvalidArgumentException(sprintf('Invalid color: %s', $hex));
        }
        
        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));
        
        $color = new self($red, $green, $blue, $alpha);
        $color->format = 'hex';
        return $color;
    }

    /**
     * Creates a Color from an RGB format string (R:G:B).
     *
     * @param string $rgb RGB color string (e.g. 255:128:0)
     * @param float|null $alpha Alpha component (0-1, null for no alpha)
     *
     * @return self
     */
    public static function fromRgbString(string $rgb, ?float $alpha = null): self
    {
        $rgbParts = explode(':', $rgb);
        
        if (count($rgbParts) !== 3) {
            throw new InvalidArgumentException(sprintf('Invalid color: %s', $rgb));
        }
        
        // Check if each part is a valid number
        foreach ($rgbParts as $part) {
            if (!is_numeric($part) || $part < 0 || $part > 255) {
                throw new InvalidArgumentException(sprintf('Invalid color: %s', $rgb));
            }
        }
        
        $red = (int) $rgbParts[0];
        $green = (int) $rgbParts[1];
        $blue = (int) $rgbParts[2];
        
        $color = new self($red, $green, $blue, $alpha);
        $color->format = 'rgb';
        return $color;
    }

    /**
     * Creates a Color from RGB components.
     *
     * @param int $red Red component (0-255)
     * @param int $green Green component (0-255)
     * @param int $blue Blue component (0-255)
     * @param float|null $alpha Alpha component (0-1, null for no alpha)
     *
     * @return self
     */
    public static function fromRgb(int $red, int $green, int $blue, ?float $alpha = null): self
    {
        return new self($red, $green, $blue, $alpha);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return self
     */
    public static function __set_state(array $data): self
    {
        $color = new self(
            $data['red'] ?? 0,
            $data['green'] ?? 0,
            $data['blue'] ?? 0,
            $data['alpha'] ?? null
        );
        
        if (isset($data['format'])) {
            $color->format = $data['format'];
        }
        
        return $color;
    }

    /**
     * Validates RGB components.
     *
     * @param int $red Red component
     * @param int $green Green component
     * @param int $blue Blue component
     *
     * @throws InvalidArgumentException
     */
    private function validateRgb(int $red, int $green, int $blue): void
    {
        if ($red < 0 || $red > 255) {
            throw new InvalidArgumentException(sprintf('Invalid red component: %d', $red));
        }
        
        if ($green < 0 || $green > 255) {
            throw new InvalidArgumentException(sprintf('Invalid green component: %d', $green));
        }
        
        if ($blue < 0 || $blue > 255) {
            throw new InvalidArgumentException(sprintf('Invalid blue component: %d', $blue));
        }
    }

    /**
     * Validates alpha component.
     *
     * @param float $alpha Alpha component
     *
     * @throws InvalidArgumentException
     */
    private function validateAlpha(float $alpha): void
    {
        if ($alpha < 0 || $alpha > 1) {
            throw new InvalidArgumentException(sprintf('Invalid alpha component: %f', $alpha));
        }
    }

    /**
     * Returns color as RGB components string (R:G:B).
     *
     * @return string
     */
    public function asRgb(): string
    {
        return sprintf('%d:%d:%d', $this->red, $this->green, $this->blue);
    }

    /**
     * Returns color as hex string without a leading hash.
     *
     * @return string
     */
    public function asHex(): string
    {
        return sprintf('%02x%02x%02x', $this->red, $this->green, $this->blue);
    }

    /**
     * Returns the color value as needed for ImgProxy URLs.
     * Uses the format that was specified when creating the color.
     *
     * @return string
     */
    public function value(): string
    {
        if ($this->format === 'rgb') {
            if ($this->alpha === null) {
                return $this->asRgb();
            }
            
            return sprintf('%s:%s', $this->asRgb(), $this->alpha);
        }
        
        // Default to hex format
        if ($this->alpha === null) {
            return $this->asHex();
        }
        
        return sprintf('%s:%s', $this->asHex(), $this->alpha);
    }
}