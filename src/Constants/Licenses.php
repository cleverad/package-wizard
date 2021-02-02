<?php

namespace Helldar\PackageWizard\Constants;

final class Licenses
{
    public const DEFAULT_LICENSE = 'unknown';

    public static function available(): array
    {
        return [
            'MIT',
            'Apache-2.0',
            'BSD-2.0',
            'Unknown',
        ];
    }

    public static function get(int $index): ?string
    {
        return self::available()[$index] ?? null;
    }
}