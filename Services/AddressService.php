<?php

namespace App\Services;

class AddressService
{
    public static function rules($isUpdate = false)
    {
        $register = [
            "state" => "required",
            "city" => "required",
            "street" => "required",
            "number" => "required",
        ];

        return $register;
    }

    public static function message()
    {
        return [
            'required' => 'O campo :attribute não foi preenchido.'
        ];
    }
}