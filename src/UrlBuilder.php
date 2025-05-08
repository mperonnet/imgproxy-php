<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy;

use Mperonnet\ImgProxy\Options\AbstractOption;
use Mperonnet\ImgProxy\Support\ImageFormat;
use Mperonnet\ImgProxy\Support\UrlEncrypter;
use Mperonnet\ImgProxy\Support\UrlSigner;

/**
 * ImgProxy URL builder
 *
 * This class is an enhanced version of the original UrlBuilder from the
 * onliner/imgproxy-php library, with added support for ImgProxy Pro features
 * and all URL formats.
 */
class UrlBuilder
{
    private const INSECURE_SIGN = 'insecure';
    private const PLAIN_PREFIX = 'plain/';
    private const ENCRYPTED_PREFIX = 'enc/';
    private const DEFAULT_SPLIT_SIZE = 16;
    private const SOURCE_URL_FORMAT_PLAIN = 'plain';
    private const SOURCE_URL_FORMAT_BASE64 = 'base64';
    private const SOURCE_URL_FORMAT_ENCRYPTED = 'encrypted';

    private ?UrlSigner $signer;
    private ?UrlEncrypter $encrypter = null;
    private string $sourceUrlFormat = self::SOURCE_URL_FORMAT_BASE64;
    private int $splitSize = self::DEFAULT_SPLIT_SIZE;

    /**
     * @var array<AbstractOption>
     */
    private array $options = [];

    /**
     * @var array<array<AbstractOption>>
     */
    private array $pipelines = [];

    /**
     * @var bool
     */
    private bool $isInfoEndpoint = false;

    /**
     * @param UrlSigner|null $signer
     */
    public function __construct(?UrlSigner $signer = null)
    {
        $this->signer = $signer;
    }

    /**
     * @param string $key
     * @param string $salt
     *
     * @return self
     */
    public static function signed(string $key, string $salt): self
    {
        return new self(new UrlSigner($key, $salt));
    }

    /**
     * @return array<AbstractOption>
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @param AbstractOption ...$options
     *
     * @return $this
     */
    public function with(AbstractOption ...$options): self
    {
        $self = clone $this;
        $self->options = array_merge($this->options, $options);

        return $self;
    }

    /**
     * Configures the builder to use base64 encoding for the source URL.
     *
     * @return $this
     */
    public function useBase64(): self
    {
        $self = clone $this;
        $self->sourceUrlFormat = self::SOURCE_URL_FORMAT_BASE64;

        return $self;
    }

    /**
     * Configures the builder to use plain source URL.
     * This is equivalent to the original `encoded(false)` method.
     *
     * @return $this
     */
    public function usePlain(): self
    {
        $self = clone $this;
        $self->sourceUrlFormat = self::SOURCE_URL_FORMAT_PLAIN;

        return $self;
    }

    /**
     * Backward compatibility method for the original API.
     *
     * @param bool $encoded
     *
     * @return $this
     */
    public function encoded(bool $encoded): self
    {
        $self = clone $this;
        $self->sourceUrlFormat = $encoded ? self::SOURCE_URL_FORMAT_BASE64 : self::SOURCE_URL_FORMAT_PLAIN;

        return $self;
    }

    /**
     * Configures the builder to use encrypted source URL (Pro feature).
     *
     * @param string $key The hex-encoded key to use for encryption
     *
     * @return $this
     */
    public function useEncryption(string $key): self
    {
        $self = clone $this;
        $self->sourceUrlFormat = self::SOURCE_URL_FORMAT_ENCRYPTED;
        $self->encrypter = new UrlEncrypter($key);

        return $self;
    }

    /**
     * @param int $size
     *
     * @return self
     */
    public function split(int $size): self
    {
        $self = clone $this;
        $self->splitSize = $size;

        return $self;
    }

    /**
     * Configure the builder to use the info endpoint (Pro feature).
     *
     * @return self
     */
    public function info(): self
    {
        $self = clone $this;
        $self->isInfoEndpoint = true;

        return $self;
    }

    /**
     * Adds a new pipeline with the given options (Pro feature).
     *
     * @param AbstractOption ...$options
     *
     * @return self
     */
    public function pipeline(AbstractOption ...$options): self
    {
        $self = clone $this;
        $self->pipelines[] = $options;

        return $self;
    }

    /**
     * @param string $src
     * @param string|null $extension
     *
     * @return string
     */
    public function url(string $src, ?string $extension = null): string
    {
        $format = $extension ? new ImageFormat($extension) : null;

        $path = $this->buildPathWithPipelines($this->source($src, $format));

        $signaturePath = $path;
        if ($this->isInfoEndpoint) {
            $path = '/info' . $path;
        }

        return sprintf('/%s%s', $this->signature($signaturePath), $path);
    }

    /**
     * Builds the URL path with options and pipelines.
     *
     * @param string $source The encoded source part of the URL
     *
     * @return string
     */
    private function buildPathWithPipelines(string $source): string
    {
        $optionsPath = implode('/', $this->options);

        // If we have pipelines, add them to the path
        if (!empty($this->pipelines)) {
            foreach ($this->pipelines as $pipeline) {
                $optionsPath .= '/-/' . implode('/', $pipeline);
            }
        }

        return sprintf('/%s/%s', $optionsPath, $source);
    }

    /**
     * @param string $src
     * @param ImageFormat|null $format
     *
     * @return string
     */
    private function source(string $src, ?ImageFormat $format): string
    {
        switch ($this->sourceUrlFormat) {
            case self::SOURCE_URL_FORMAT_PLAIN:
                return $this->plainSource($src, $format);
            case self::SOURCE_URL_FORMAT_ENCRYPTED:
                return $this->encryptedSource($src, $format);
            case self::SOURCE_URL_FORMAT_BASE64:
            default:
                return $this->base64Source($src, $format);
        }
    }

    /**
     * Formats the source URL using plain format.
     *
     * @param string $src
     * @param ImageFormat|null $format
     *
     * @return string
     */
    private function plainSource(string $src, ?ImageFormat $format): string
    {
        $sep = '@';
        $source = str_replace($sep, '%40', self::PLAIN_PREFIX . $src);

        $extension = null;
        if ($format && !$format->isEquals($this->extension($src))) {
            $extension = $format->value();
        }

        return implode($sep, array_filter([$source, $extension]));
    }

    /**
     * Formats the source URL using base64 encoding.
     *
     * @param string $src
     * @param ImageFormat|null $format
     *
     * @return string
     */
    private function base64Source(string $src, ?ImageFormat $format): string
    {
        $sep = '.';
        $source = $this->encode($src);

        if ($this->splitSize > 0) {
            $source = wordwrap($source, $this->splitSize, '/', true);
        }

        $extension = null;
        if ($format) {
            $extension = $format->value();
        }

        return implode($sep, array_filter([$source, $extension]));
    }

    /**
     * Formats the source URL using encryption (Pro feature).
     *
     * @param string $src
     * @param ImageFormat|null $format
     *
     * @return string
     */
    private function encryptedSource(string $src, ?ImageFormat $format): string
    {
        if ($this->encrypter === null) {
            throw new \RuntimeException('URL encrypter is not configured');
        }

        $sep = '.';
        $source = self::ENCRYPTED_PREFIX . $this->encrypter->encrypt($src);

        if ($this->splitSize > 0) {
            $parts = explode('/', $source, 2);
            if (count($parts) > 1) {
                $prefix = $parts[0] . '/';
                $encrypted = wordwrap($parts[1], $this->splitSize, '/', true);
                $source = $prefix . $encrypted;
            }
        }

        $extension = null;
        if ($format) {
            $extension = $format->value();
        }

        return implode($sep, array_filter([$source, $extension]));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function encode(string $url): string
    {
        return rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function signature(string $path): string
    {
        if ($this->signer !== null) {
            return $this->encode($this->signer->sign($path));
        }

        return self::INSECURE_SIGN;
    }

    /**
     * @param string $src
     *
     * @return string
     */
    private function extension(string $src): string
    {
        return pathinfo(parse_url($src, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION);
    }
}
