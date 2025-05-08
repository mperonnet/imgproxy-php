<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class WatermarkUrl extends AbstractOption
{
    private string $url;

    /**
     * @param string $url URL of the watermark image, will be base64-encoded in the URL
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wmu';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->encodeUrl($this->url),
        ];
    }

    /**
     * Encodes the URL to be used in the ImgProxy URL.
     *
     * @param string $url
     *
     * @return string
     */
    private function encodeUrl(string $url): string
    {
        return rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
    }
}
