<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

final class Resize extends AbstractOption
{
    private ResizingType $type;
    private ?Size $size = null;

    public function __construct(
        string $type,
        ?int $width = null,
        ?int $height = null,
        ?bool $enlarge = null,
        ?bool $extend = null
    ) {
        $this->type = new ResizingType($type);

        if (!is_null($width ?? $height ?? $enlarge ?? $extend)) {
            $this->size = new Size($width, $height, $enlarge, $extend);
        }
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'rs';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return array_merge(
            $this->type->data(),
            $this->size ? $this->size->data() : []
        );
    }
}
