<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersService
{
    public static function rules($isUpdate = false, $id = null)
    {
        $register = [
            "name" => [
                "required",
                "regex:/^(?=.*[A-Za-z])[A-Za-zÁ-Ñá-ñ ]*$/i"
            ],
            "email" => ["required", "unique:users", "email"],
            "cpf" => [
                "required",
                "unique:users",
                "min:11",
                "regex:/^(?=.*\d)[\d]{11}$/i"
            ],
            "password" => [
                "required",
                "confirmed",
                "min:8",
                "regex:/^(?=.*\w)(?=.*\d)(?=.*[@*._-])[\w\d@*._-]*$/i"
            ],
            "password_confirmation" => ["required"],
        ];

        $update = [
            "name" => [
                "required",
                "regex:/^(?=.*[A-Za-z])[A-Za-zÁ-Ñá-ñ ]*$/i"
            ],
            "email" => [
                "required",
                "email",
                Rule::unique('users', 'email')->ignore($id),
            ],
            "cpf" => [
                "required",
                "min:11",
                "regex:/^(?=.*\d)[\d]{11}$/i",
                Rule::unique('users', 'cpf')->ignore($id),
            ]
        ];

        return ($isUpdate) ? $update: $register;
    }

    public static function message()
    {
        return [
            "name.regex"
                => "Nome deve ter somente letrars e espaços.",
            "email"
                => "Endereço de :attribute inválido.",
            "cpf.min" 
                => ":Attribute deve ter ao menos 11 caracteres.",
            "cpf.regex"
                => ":Attribute inválido.",
            "password.regex" 
                => "Senha deve ter ao menos uma letra, número e caractere especial @*._-",
            "password.min" 
                => "Senha deve ter ao menos 8 caracteres.",
            "password.confirmed" 
                => "Confirmação de senha não corresponde.",
            "password_confirmation.required"
                => "O campo confirmação de senha é necessário.",
            "unique"
                => ":Attribute já está em uso.",
            "required"
                => "O campo :attribute é necessário.",
        ];
    }

    public static function hashPassword($password)
    {
        return Hash::make($password);
    }
}