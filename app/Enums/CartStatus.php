<?php

namespace App\Enums;

use App\Enums\Concerns\HasOptions;

enum CartStatus: string
{
    use HasOptions;

    case Active = 'active';
    case Converted = 'converted';
    case Abandoned = 'abandoned';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Converted => 'Converted',
            self::Abandoned => 'Abandoned',
        };
    }
}
