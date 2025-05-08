<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

use InvalidArgumentException;

final class Autoquality extends AbstractOption
{
    public const METHOD_NONE = 'none';
    public const METHOD_SIZE = 'size';
    public const METHOD_DSSIM = 'dssim';
    public const METHOD_ML = 'ml';
    
    private ?string $method;
    private ?float $target;
    private ?int $minQuality;
    private ?int $maxQuality;
    private ?float $allowedError;

    /**
     * @param string|null $method Autoquality method ('none', 'size', 'dssim', 'ml')
     * @param float|null $target Target value (file size in bytes for 'size' method, DSSIM value for 'dssim' and 'ml' methods)
     * @param int|null $minQuality Minimum quality value (1-100)
     * @param int|null $maxQuality Maximum quality value (1-100)
     * @param float|null $allowedError Allowed error for 'dssim' and 'ml' methods
     */
    public function __construct(
        ?string $method = null,
        ?float $target = null,
        ?int $minQuality = null,
        ?int $maxQuality = null,
        ?float $allowedError = null
    ) {
        if ($method !== null && !in_array($method, $this->getMethods())) {
            throw new InvalidArgumentException(sprintf('Invalid autoquality method: %s', $method));
        }
        
        if ($minQuality !== null && ($minQuality < 1 || $minQuality > 100)) {
            throw new InvalidArgumentException(sprintf('Invalid min quality: %s (should be between 1 and 100)', $minQuality));
        }
        
        if ($maxQuality !== null && ($maxQuality < 1 || $maxQuality > 100)) {
            throw new InvalidArgumentException(sprintf('Invalid max quality: %s (should be between 1 and 100)', $maxQuality));
        }
        
        if ($minQuality !== null && $maxQuality !== null && $minQuality > $maxQuality) {
            throw new InvalidArgumentException(sprintf('Min quality (%s) cannot be greater than max quality (%s)', $minQuality, $maxQuality));
        }
        
        if ($allowedError !== null && $allowedError < 0) {
            throw new InvalidArgumentException(sprintf('Invalid allowed error: %s (should be greater than or equal to 0)', $allowedError));
        }

        $this->method = $method;
        $this->target = $target;
        $this->minQuality = $minQuality;
        $this->maxQuality = $maxQuality;
        $this->allowedError = $allowedError;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'aq';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->method,
            $this->target,
            $this->minQuality,
            $this->maxQuality,
            $this->allowedError,
        ];
    }
    
    /**
     * @return array<string>
     */
    private function getMethods(): array
    {
        return [
            self::METHOD_NONE,
            self::METHOD_SIZE,
            self::METHOD_DSSIM,
            self::METHOD_ML,
        ];
    }
    
    /**
     * Create an autoquality option with the size method.
     *
     * @param float $targetSize Target file size in bytes
     * @param int|null $minQuality Minimum quality value (1-100)
     * @param int|null $maxQuality Maximum quality value (1-100)
     *
     * @return self
     */
    public static function size(float $targetSize, ?int $minQuality = null, ?int $maxQuality = null): self
    {
        return new self(self::METHOD_SIZE, $targetSize, $minQuality, $maxQuality);
    }
    
    /**
     * Create an autoquality option with the DSSIM method.
     *
     * @param float $targetDssim Target DSSIM value
     * @param int|null $minQuality Minimum quality value (1-100)
     * @param int|null $maxQuality Maximum quality value (1-100)
     * @param float|null $allowedError Allowed error for DSSIM
     *
     * @return self
     */
    public static function dssim(
        float $targetDssim,
        ?int $minQuality = null,
        ?int $maxQuality = null,
        ?float $allowedError = null
    ): self {
        return new self(self::METHOD_DSSIM, $targetDssim, $minQuality, $maxQuality, $allowedError);
    }
    
    /**
     * Create an autoquality option with the ML method.
     *
     * @param float $targetDssim Target DSSIM value
     * @param int|null $minQuality Minimum quality value (1-100)
     * @param int|null $maxQuality Maximum quality value (1-100)
     * @param float|null $allowedError Allowed error for DSSIM
     *
     * @return self
     */
    public static function ml(
        float $targetDssim,
        ?int $minQuality = null,
        ?int $maxQuality = null,
        ?float $allowedError = null
    ): self {
        return new self(self::METHOD_ML, $targetDssim, $minQuality, $maxQuality, $allowedError);
    }
    
    /**
     * Create an autoquality option with the none method (disable autoquality).
     *
     * @return self
     */
    public static function none(): self
    {
        return new self(self::METHOD_NONE);
    }
}