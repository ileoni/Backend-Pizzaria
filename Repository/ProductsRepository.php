<?php

namespace App\Repository;

use App\Models\Products as Eloquent;
use App\Services\ProductsService;
use Illuminate\Http\Request;

class ProductsRepository implements ProductsInterface
{
    private $eloquent;
    public function __construct(Eloquent $eloquent)
    {
        $this->eloquent = $eloquent;
    }    

    public function register(Request $request)
    {
        $product = $this->eloquent;
        $product->name = request('name');
        $product->description = request('description');
        $product->price = request('price');
        
        $path = ProductsService::uploadImage('images', request('image'));
        $product->path = $path;
        
        $product->save();

        return [
            "data" => [
                "success" => true
            ],
            "status" => 201
        ];
    }

    public function update(Request $request, $id)
    {
        $product = $this->eloquent->find($id);
        $oldPath = $product->path;

        if(!$product) return [
            "data" => [
                "success" => true,
                "message" => "Produto não foi encontrado."
            ],
            "status" => 401
        ];

        $product->name = request('name');
        $product->description = request('description');
        $product->price = request('price');
        
        $path = ProductsService::uploadImage('images', request('image'));
        $product->path = $path;

        $product->update();

        unlink($oldPath);


        return [
            "data" => [
                "success" => true
            ],
            "status" => 201
        ];
        
    }

    public function list()
    {
        $products = $this->eloquent->select(['*']);

        $products = $products->get();

        return [
            "data" => [
                "success" => true,
                "data" => $products
            ],
            "status" => 200
        ];
        
    }

    public function remove($id)
    {
        $product = $this->eloquent->find($id);
        $path = $product->path;

        if(!$product) return [
            "data" => [
                "success" => true,
                "message" => "Produto não foi encontrado."
            ],
            "status" => 401
        ];

        $product->delete();

        unlink($path);

        return [
            "data" => [
                "success" => true,
                "ID" => $product->id
            ],
            "status" => 200
        ];
        
    }

    public function findById($id)
    {
        $product = $this->eloquent->find($id);

        if(!$product) return [
            "data" => [
                "success" => true,
                "message" => "Produto não foi encontrado."
            ],
            "status" => 401
        ];
        
        $product = $product->only('id', 'name','description', 'price', 'path');

        return [
            "data" => [
                "success" => true,
                "data" => $product
            ],
            "status" => 200
        ];        
    }
}