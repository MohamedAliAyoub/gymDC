<?php
// app/Enums/UserType.php
namespace App\Enums;

enum UserTypeEnum: int {
    case Admin = 0;
    case OperationManager = 1;
    case Owner = 2;
    case Doctor = 3;
    case Coach = 4;
    case Sales = 5;
    case FollowUp = 6;
    case TeamLead = 7;
    case Client = 8;

    public static function fromValue(int $value): self {
        return match($value) {
            0 => self::Admin,
            1 => self::OperationManager,
            2 => self::Owner,
            3 => self::Doctor,
            4 => self::Coach,
            5 => self::Sales,
            6 => self::FollowUp,
            7 => self::TeamLead,
            8 => self::Client,
            default => throw new \UnexpectedValueException("Invalid user type value: $value"),
        };
    }

    public static function getValues(): array {
        return array_column(self::cases(), 'value');
    }

    public static function getKeyValuePairs(): array {
        $cases = self::cases();
        $keyValuePairs = [];
        foreach ($cases as $case) {
            $keyValuePairs[$case->name] =   $case->value;
        }
        return $keyValuePairs;
    }
}
