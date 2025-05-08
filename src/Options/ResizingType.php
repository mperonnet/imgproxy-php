<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use InvalidArgumentException;

final class ResizingType extends AbstractOption
{
    public const
        FIT = 'fit',
        FILL = 'fill',
        FILL_DOWN = 'fill-down',
        FORCE = 'force',
        AUTO = 'auto'
    ;

    private const TYPES = [
        self::FIT,
        self::FILL,
        self::FILL_DOWN,
        self::FORCE,
        self::AUTO,
    ];

    private string $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException(sprintf('Invalid resizing type: %s', $type));
        }

        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'rt';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            $this->type,
        ];
    }
}
