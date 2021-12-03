<?php

namespace App\Http\Controllers;

use App\Models\Sizes;
use App\Repository\SizesRepository;

class SizesController extends Controller
{
    private $sizesRepository;
    public function __construct(Sizes $sizes, SizesRepository $sizesRepository)
    {
        $this->sizes = $sizes;
        $this->sizesRepository = $sizesRepository;
    }

    public function list()
    {
        try {
            $response = $this->sizesRepository->list();
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }
    }
    
    public function findById($id)
    {
        try {
            $response = $this->sizesRepository->findById($id);
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }
    }
}