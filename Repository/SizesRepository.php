<?php

namespace App\Repository;

use App\Models\Sizes as Eloquent;

class SizesRepository implements SizesInterface
{
    private $eloquent;
    public function __construct(Eloquent $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    public function list()
    {
        $sizes = $this->eloquent->select(['id', 'name', 'size']);

        $sizes = $sizes->get();

        return [
            "data" => [
                "success" => true,
                "data" => $sizes
            ],
            "status" => 200 
        ];
    }

    public function findById($id)
    {
        $size = $this->eloquent->find($id);

        if(!$size) return [
            "data" => [
                "success" => false,
                "message" => "Tamanho não foi encontrado." 
            ],
            "status" => 200 
        ];

        $size = $size->only('id', 'name', 'size');

        return [
            "data" => [
                "success" => true,
                "data" => $size
            ],
            "status" => 200 
        ];       
    }
}