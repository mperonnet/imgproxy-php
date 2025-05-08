<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Options;

use ReflectionClass;
use Stringable;

abstract class AbstractOption implements Stringable
{
    /**
     * @param array<string, mixed> $data
     *
     * @return static
     */
    public static function __set_state(array $data): static
    {
        $class = new ReflectionClass(static::class);
        $self = $class->newInstanceWithoutConstructor();

        $assigner = function () use ($self, $data) {
            foreach ($data as $key => $value) {
                $self->{$key} = $value;
            }
        };
        $assigner->bindTo($self, static::class)();

        return $self;
    }

    /**
     * Returns the option name used in the ImgProxy URL.
     * Can be the shorthand version (e.g., 'w' for width) or the full name (e.g., 'width').
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * Returns the option data.
     * These are the values that will be used as arguments in the option.
     *
     * @return array<mixed>
     */
    abstract public function data(): array;

    /**
     * Formats a boolean value for ImgProxy URL.
     *
     * @param bool|null $value The boolean value to format
     *
     * @return string|null The formatted value as a string or null if value is null
     */
    protected function formatBoolean(?bool $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value ? '1' : '0';
    }

    /**
     * Formats a value to be used in a URL.
     *
     * @param mixed $value The value to format
     *
     * @return string|null Formatted value or null if the value should be omitted
     */
    protected function formatValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $this->formatBoolean($value);
        }

        if (is_float($value)) {
            // Remove trailing zeros for floating point values
            return rtrim(rtrim(sprintf('%.6f', $value), '0'), '.');
        }

        return (string) $value;
    }

    /**
     * Returns the string representation of the option to be used in the URL.
     *
     * @return string
     */
    public function value(): string
    {
        $values = [];
        $data = $this->data();

        // First value is always the option name
        $values[] = $this->name();

        // Process the data values
        foreach ($data as $key => $value) {
            $formattedValue = $this->formatValue($value);

            // Add the value to our array
            $values[] = $formattedValue;
        }

        // Filter out null values from the end of the array
        while (count($values) > 1 && end($values) === null) {
            array_pop($values);
        }

        // Replace remaining null values with empty strings to maintain positions
        foreach ($values as &$value) {
            if ($value === null) {
                $value = '';
            }
        }

        return implode(':', $values);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
