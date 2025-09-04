<?php

namespace App\Enums;

enum UserStatus:string
{
    //
    case ACTIVE='active';
    case INACTIVE="inactive";
    case BANNED='banned';

    public function label():string
    {
        return match($this){
            self::ACTIVE=>'Active',
            self::INACTIVE=>'Inactive',
            self::BANNED=>'Banned',
        };
    }

    // public static function toArray(): array
    // {
    //     return array_map(fn($case) => [
    //         'value' => $case->value,
    //         'label' => $case->label(),
    //     ], self::cases());
    // }
    public function labelPowergridFilter(): string
    {
        return $this->label();
    }

}
