<?php

namespace App\Repository;

use App\Models\Address;
use App\Models\User as Eloquent;
use App\Services\UsersService;
use Illuminate\Http\Request;

class UsersRepository implements UsersRepositoryInterface
{
    private $eloquent;
    private $address;
    public function __construct(Eloquent $eloquent, Address $address)
    {
        $this->eloquent = $eloquent;
        $this->address = $address;
    }

    public function register(Request $request)
    {
        $nonAdmin = 'N';
        $admin = $request->input('admin');

        $user = $this->eloquent;
        $user->admin    = !$admin ? $nonAdmin: $admin;
        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->cpf      = $request->input('cpf');
        $user->password = UsersService::hashPassword($request->input('password'));

        $user->save();
        
        return [
            "data" => [
                'success' => true,
                'data' => [
                    'message' => [
                        'user' => ['Usuário cadastrado com sucesso.']
                    ]
                ]
            ],
            "status" => 201
        ];
    }

    public function update(Request $request, $id)
    {
        $user = $this->eloquent->find($id);
        
        if(!$user) return [
            "data" => [
                "success" => false, 
                "message" => 'usuário não foi encontrado.',
            ],
            "status" => 401
        ];

        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->cpf      = $request->input('cpf');
        
        $user->update();

        return [
            "data" => [
                "success" => true
            ],
            "status" => 201
        ];
    }

    public function list()
    {
        $user = $this->eloquent->select('*');  
        $user = $user->get();

        return [
            "data" => [
                "success" => true,
                "data" => $user
            ],
            "status" => 200
        ];
    }

    public function remove($id)
    {
        $user = $this->eloquent->find($id);
    
        if(!$user) return [
            "data" => [
                "success" => false,
                'message' => 'Usuário não foi encontrado.'    
            ],
            "status" => 401
        ];

        $user->address()->delete();
        $user->delete();

        return [
            "data" => [
                'success' => true,
                'ID' => $user->id
            ],
            "status" => 200
        ];
    }

    public function findById($id)
    {    
        $user = $this->eloquent->find($id);
            
        if(!$user) return [
            "data" => [
                "success" => false,
                'message' => 'Usuário não foi encontrado.'
            ],
            "status" => 401
        ];

        $user = $user->only("id", "name", 'email');
        
        return [
            "data" => [
                'success' => true,
                'data' => $user
            ],
            "status" => 200
        ];
    }
}