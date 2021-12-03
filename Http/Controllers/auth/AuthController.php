<?php
namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function Rules()
    {
        return [
            'email'=> ['required', 'email', 'exists:users,email'],
            'password' => ['required'] 
        ];
    }

    protected function Message()
    {
        return [
            'required' => 'O campo :attribute é nescessário.',
            'email' => ':Attribute invalido.',
            'email.exists' => ':Attribute inserido não existe.'
        ];
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->Rules(), $this->Message());
        if($validator->fails()) return response()->json([
                'success' => false,
                'data' => [
                    'message' => $validator->errors()
                ]
        ], 401);

        try {
            $credentials = request(['email', 'password']);
            
            if(!$token = Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'message' => [
                            'auth' => ['Email ou senha incorreta.']
                        ]
                    ]
                ], 401);
            }
    
            return $this->respondWithToken($token);       
        } catch (\Exception $e) {
            return response()->json($e, 401);    
        }
    }

    public function logout()
    {
        Auth::logout();
        
        return response()->json([
            'success' => true
        ], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }
    
    protected function respondWithToken($token)
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => [
                    'auth' => ['Bem vindo!']
                ],
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' =>  [
                    'id' => $user->id,
                    'name' => $user->name,
                    'admin' => $user->admin,
                ]
            ]
        ], 200);
    }
}