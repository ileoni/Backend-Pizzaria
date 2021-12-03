<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Repository\ProductsRepository;
use App\Services\ProductsService;

class ProductsController extends Controller
{
    private $products;
    private $productsRepository;
    public function __construct(Products $products, ProductsRepository $productsRepository)
    {
        $this->middleware('auth', [ 'except' => ['list', 'findById']]);
        $this->products = $products;
        $this->productsRepository = $productsRepository;
    }

    public function register()
    {
        $this->validate(
            request(), ProductsService::rules(), ProductsService::message()
        );

        try {
            $response = $this->productsRepository->register(request());
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }
    }

    public function update($id)
    {
        $this->validate(
            request(), ProductsService::rules(), ProductsService::message()
        );
        
        try {
            $response = $this->productsRepository->update(request(), $id);
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function list()
    {
        try {
            $response = $this->productsRepository->list();
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }
    }

    public function remove($id)
    {
        try {
            $response = $this->productsRepository->remove($id);
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function findById($id)
    {
        try {
            $response = $this->productsRepository->findById($id);
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}