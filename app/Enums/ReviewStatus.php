<?php

namespace App\Enums;

use App\Enums\Concerns\HasOptions;

enum ReviewStatus: string
{
    use HasOptions;

    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function isApproved(): bool
    {
        return $this === self::Approved;
    }
}
