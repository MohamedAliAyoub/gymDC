<?php
// app/Enums/SubscriptionStatus.php
namespace App\Enums;

enum SubscriptionStatusEnum: int {
    case NotStarted = 0;
    case Active = 1;
    case Expired = 2;
    case Freezed = 3;
    case Refunded = 4;

    public static function fromValue(int $value): SubscriptionStatusEnum
    {
        return match($value) {
            0 => self::NotStarted,
            1 => self::Active,
            2 => self::Expired,
            3 => self::Freezed,
            4 => self::Refunded,
        };
    }

    public static function fromKey($value)
    {
        return match($value) {
            'NotStarted' => self::NotStarted,
            'Active' => self::Active,
            'Expired' => self::Expired,
            'Freezed' => self::Freezed,
            'Refunded' => self::Refunded,
        };
    }
}
