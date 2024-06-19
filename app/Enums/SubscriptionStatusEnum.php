<?php
// app/Enums/SubscriptionStatus.php
namespace App\Enums;

enum SubscriptionStatusEnum: int {
    case NotStarted = 0;
    case Active = 1;
    case Expired = 2;
    case Freezed = 3;
    case Refunded = 4;

    public static function fromValue(int $value): self {
        return match($value) {
            0 => self::NotStarted,
            1 => self::Active,
            2 => self::Expired,
            3 => self::Freezed,
            4 => self::Refunded,
            default => throw new \UnexpectedValueException("Invalid subscription status value: $value"),
        };
    }
}
