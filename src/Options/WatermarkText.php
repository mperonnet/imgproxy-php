<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Options;

final class WatermarkText extends AbstractOption
{
    private string $text;

    /**
     * @param string $text Text to use as a watermark, will be base64-encoded in the URL.
     *                     Can contain Pango markup for styling.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'wmt';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->encodeText($this->text),
        ];
    }
    
    /**
     * Encodes the text to be used in the ImgProxy URL.
     *
     * @param string $text
     *
     * @return string
     */
    private function encodeText(string $text): string
    {
        return rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
    }
}