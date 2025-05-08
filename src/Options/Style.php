<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class Style extends AbstractOption
{
    private string $style;

    /**
     * @param string $style CSS styles to be added to the SVG
     */
    public function __construct(string $style)
    {
        $this->style = $style;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'st';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->encodeStyle($this->style),
        ];
    }

    /**
     * Encodes the CSS styles to be used in the ImgProxy URL.
     *
     * @param string $style
     *
     * @return string
     */
    private function encodeStyle(string $style): string
    {
        return rtrim(strtr(base64_encode($style), '+/', '-_'), '=');
    }
}
