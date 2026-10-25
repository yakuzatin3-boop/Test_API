<?php

namespace App\Enums;

use App\Enums\Concerns\HasOptions;

enum ProductStatus: string
{
    use HasOptions;

    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Archived => 'Archived',
        };
    }

    public function isSellable(): bool
    {
        return $this === self::Active;
    }
}
