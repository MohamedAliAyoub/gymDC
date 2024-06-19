<?php
// app/Enums/FormStatusEnum.php
namespace App\Enums;

enum FormStatusEnum: int {
    case FirstFormNeeded = 0;
    case UpdateNeeded = 1;
    case AllReady = 2;

    public static function fromValue(int $value): self {
        return match($value) {
            0 => self::FirstFormNeeded,
            1 => self::UpdateNeeded,
            2 => self::AllReady,
            default => throw new \UnexpectedValueException("Invalid form status value: $value"),
        };
    }
}
