<?php

namespace App\Services;

class Badge
{
    public const VERT = '#2e7d4f';

    public const ORANGE = '#c2770a';

    public const ROUGE = '#b42318';

    public const GRIS = '#6b7280';

    public static function couleur(?int $note): string
    {
        return match (true) {
            $note === null => self::GRIS,
            $note >= 90 => self::VERT,
            $note >= 50 => self::ORANGE,
            default => self::ROUGE,
        };
    }
}
