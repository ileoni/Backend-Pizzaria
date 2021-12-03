<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\UsersRepository;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $user;
    private $usersRepository;
    public function __construct(
        User $user,
        UsersRepository $usersRepository
    )
    {
        $this->middleware('auth', ['except' => ['register']]);
        $this->user = $user;
        $this->usersRepository = $usersRepository;
    }

    public function register(Request $request)
    {        
        $validator = Validator::make($request->all(), UsersService::rules(), UsersService::message());
        if($validator->fails()) return response()->json([
                'success' => false,
                'data' => [
                    'message' => $validator->errors()
                ]
        ], 401);

        try {
            $response = $this->usersRepository->register(request());
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => [
                        'error' => [$e]
                    ]
                ]
            ], 400);
        }
    }

    public function update($id)
    {
        $this->validate(
            request(), UsersService::rules(true, $id), UsersService::message()
        );
        
        try {
            $response = $this->usersRepository->update(request(), $id);
            
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }

    public function list()
    {
        try {
            $response = $this->usersRepository->list();
            
            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }

    public function remove($id)
    {
        try {
            $response = $this->usersRepository->remove($id);

            return response()->json($response['data'], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }

    
    public function findById($id)
    {
        try {
            $response = $this->usersRepository->findById($id);
            
            return response()->json($response["data"], $response['status']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }
}
