<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Repository\AddressRepository;
use App\Services\AddressService;

class AddressController extends Controller
{
    private $address;
    private $addressRepository;
    public function __construct(Address $address, AddressRepository $addressRepository)
    {
        $this->middleware('auth', [ 'except' => ['register']]);
        $this->address = $address;
        $this->addressRepository = $addressRepository;
    }   

    public function register()
    {
        $this->validate(
            request(), AddressService::rules(), AddressService::message()
        );

        try {
            $response = $this->addressRepository->register(request());
            
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }

    }

    public function update($id)
    {
        $this->validate(
            request(), AddressService::rules(), AddressService::message()
        );
        
        try {
            $response = $this->addressRepository->update(request(), $id);
            
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }

    }

    public function list()
    {
        try {
            $response = $this->addressRepository->list();
    
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e ], 400);
        }
    }

    public function remove($id)
    {
        try {
            $response = $this->addressRepository->remove($id);

            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }

    public function findById($id)
    {
        try {
            $response = $this->addressRepository->findById($id);
            
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}