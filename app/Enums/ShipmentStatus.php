<?php

namespace App\Enums;

use App\Enums\Concerns\HasOptions;

enum ShipmentStatus: string
{
    use HasOptions;

    case Pending = 'pending';
    case Shipped = 'shipped';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Returned = 'returned';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Shipped => 'Shipped',
            self::InTransit => 'In Transit',
            self::Delivered => 'Delivered',
            self::Returned => 'Returned',
            self::Cancelled => 'Cancelled',
        };
    }
}
