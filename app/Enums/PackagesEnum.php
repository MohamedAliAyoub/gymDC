<?php
// app/Enums/SubscriptionStatus.php
namespace App\Enums;

enum PackagesEnum: int {
    case Standard = 0;
    case VIP = 1;

    public static function fromValue(int $value): self {
        return match($value) {
            0 => self::Standard,
            1 => self::VIP,
            default => throw new \UnexpectedValueException("Invalid subscription status value: $value"),
        };
    }
}
