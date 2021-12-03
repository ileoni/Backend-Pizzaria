<?php

namespace App\Repository;

use App\Models\Address as Eloquent;
use Illuminate\Http\Request;

class AddressRepository implements AddressInterface
{
    private $eloquent;
    public function __construct(Eloquent $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    public function register(Request $request)
    {            
        $userId = auth()->user()->id;

        $address = $this->eloquent;
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->street = $request->input('street');
        $address->number = $request->input('number');
        $address->type = $request->input('type');
        $address->description = $request->input('description');
        $address->user_id = $userId;
        $address->save();

        return [
            'data' => [
                'success' => true,
            ],
            'status' => 201
        ];
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->user()->id;
        $address = $this->eloquent->find($id);

        if(!$address) return [
            'data' => [
                'success' => false,
                'message' => 'Endereço não foi encontrado.'
            ],
            'status' => 401
        ];

        $address->state = request('state');
        $address->city = request('city');
        $address->street = request('street');
        $address->number = request('number');
        $address->type = request('type');
        $address->description = request('description');
        $address->user_id = $userId;
        $address->update();

        return [
            'data' => [
                'success' => true
            ],
            'status' => 201
        ];
    }
    
    public function list()
    {
        $address = $this->eloquent->select('*');
        $address = $address->get();

        return [
            'data' => [
                'success' => true,
                'data' => $address
            ],
            'status' => 200
        ];
    }

    public function remove($id)
    {
        $address = $this->eloquent->find($id);

        if(!$address) return [
            'data' => [
                'success' => false,
                'message' => 'Endereço não foi encontrado.'
            ],
            'status' => 401
        ];

        $address->delete();

        return [
            'data' => [
                'success' => true,
                'ID' => $address->id
            ],
            'status' => 200
        ];
    }

    public function findById($id)
    {
        $address = $this->eloquent->find($id);

        if(!$address) return [
            'data' => [
                'success' => false,
                'message' => 'Endereço não foi encontrado.'
            ],
            'status' => 401
        ];
        
        $address = $address->only('id', 'state', 'city', 'street', 'number', 'type', 'description');

        return [
            'data' => [
                'success' => true,
                'data' => $address
            ],
            'status' => 200
        ];
    }
}