<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class Hashsum extends AbstractOption
{
    public const TYPE_NONE = 'none';
    public const TYPE_MD5 = 'md5';
    public const TYPE_SHA1 = 'sha1';
    public const TYPE_SHA256 = 'sha256';
    public const TYPE_SHA512 = 'sha512';

    private string $type;
    private ?string $hashsum;

    /**
     * @param string $type Hashsum type (none, md5, sha1, sha256, sha512)
     * @param string|null $hashsum Expected hashsum value (not needed for 'none' type)
     */
    public function __construct(string $type, ?string $hashsum = null)
    {
        $validTypes = [self::TYPE_NONE, self::TYPE_MD5, self::TYPE_SHA1, self::TYPE_SHA256, self::TYPE_SHA512];

        if (!in_array($type, $validTypes)) {
            throw new InvalidArgumentException(
                sprintf('Invalid hashsum type: %s (should be none, md5, sha1, sha256, or sha512)', $type)
            );
        }

        if ($type !== self::TYPE_NONE && empty($hashsum)) {
            throw new InvalidArgumentException(sprintf('Hashsum value is required for type: %s', $type));
        }

        $this->type = $type;
        $this->hashsum = $hashsum;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'hs';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        if ($this->type === self::TYPE_NONE) {
            return [$this->type];
        }

        return [
            $this->type,
            $this->hashsum,
        ];
    }

    /**
     * Create a hashsum option with MD5 type.
     *
     * @param string $hashsum MD5 hashsum value
     *
     * @return self
     */
    public static function md5(string $hashsum): self
    {
        return new self(self::TYPE_MD5, $hashsum);
    }

    /**
     * Create a hashsum option with SHA1 type.
     *
     * @param string $hashsum SHA1 hashsum value
     *
     * @return self
     */
    public static function sha1(string $hashsum): self
    {
        return new self(self::TYPE_SHA1, $hashsum);
    }

    /**
     * Create a hashsum option with SHA256 type.
     *
     * @param string $hashsum SHA256 hashsum value
     *
     * @return self
     */
    public static function sha256(string $hashsum): self
    {
        return new self(self::TYPE_SHA256, $hashsum);
    }

    /**
     * Create a hashsum option with SHA512 type.
     *
     * @param string $hashsum SHA512 hashsum value
     *
     * @return self
     */
    public static function sha512(string $hashsum): self
    {
        return new self(self::TYPE_SHA512, $hashsum);
    }

    /**
     * Create a hashsum option with None type (disable hashsum checking).
     *
     * @return self
     */
    public static function none(): self
    {
        return new self(self::TYPE_NONE);
    }
}
