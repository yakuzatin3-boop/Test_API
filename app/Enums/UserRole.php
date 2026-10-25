<?php

namespace App\Enums;

use App\Enums\Concerns\HasOptions;

enum UserRole: string
{
    use HasOptions;

    case Admin = 'admin';
    case Staff = 'staff';
    case Customer = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Staff => 'Staff',
            self::Customer => 'Customer',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isStaff(): bool
    {
        return $this === self::Staff;
    }
}
