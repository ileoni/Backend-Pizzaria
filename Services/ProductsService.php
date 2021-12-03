<?php

namespace App\Services;

class ProductsService
{
    public static function rules()
    {
        return [
            "name" => 'required',
            "description" => 'required',
            "price" => ['required', 'regex:/(^[0-9]{1,3}[.,][0-9]{2}$)/i'],
            "image" => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ];
    }

    public static function message()
    {
        return [
            "required" => "O campo nome :attribute é necessário.",
            "image.image" => "Selecione uma imagem válida.",
            "mimes" => "A :attribute deve ser um arquivo do tipo: :values."
        ];
    }

    public static function public_path($path = null)
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '\\' . $path: $path);
    }

    public static function uploadImage($path = null, $request)
    {
        $name = time() . '.' . $request->extension();
        $request->move(self::public_path($path), $name);

        return $path . '/' . $name;
    }
}