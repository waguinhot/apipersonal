<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        $user = User::where('email' , $credentials['email'])->first();

        if($user->status != 1)
        {
            return response(
                json_encode([
                    'status' => 'error, user has ben block',
                    'message' => 'Unauthorized',
                ]),401
            ); 
        }

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response(
                json_encode([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ]),401
            );
        }

        $user = Auth::user();
        return response(json_encode(
            [
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]
        ));

    }

    public function register(RegisterRequest $request){
        
        $user = Auth::user();

        if($user->type != 1)
        {
            return response(json_encode([
                [
                    'status' => 'error',
                    'message' => 'User not created, unauthorized',
                    
                ]
            ]), 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response(json_encode([
            [
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]
        ]), 201);
    }

    public function logout()
    {
        Auth::logout();
        return response(json_encode(
            [
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]
        ));
    }

    public function refresh()
    {
        return response(json_encode(
            [
                'status' => 'success',
                'user' => Auth::user(),
                'authorization' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]
        ));
    }

    public function validToken()
    {
        return response(json_encode(
            [
                'status' => 'success',
                'user' => Auth::user(),
                'valid' => true 
            ]
        ));
    }

    public function accountBlock(int $id) 
    {
        $userreq = Auth::user();

        if($userreq->type != 1)
        {
            return response(json_encode([
                [
                    'status' => 'error',
                    'message' => 'User not blocked, unauthorized',
                    
                ]
            ]), 401);
        }

        $userblock = User::find($id);

        if(!$userblock)
        {
            return response(json_encode([
                [
                    'status' => 'error',
                    'message' => 'User not found',
                    
                ]
            ]), 404);
        }

        $userblock->status = 0;
        $userblock->save();

        return response(json_encode([
            [
                'status' => 'success',
                'message' => 'User'.$userblock->name.' block with success!',
                
            ]
        ]), 201);



    }


    public function unblockUser($id)
    {

        $userreq = Auth::user();

        if($userreq->type != 1)
        {
            return response(json_encode([
                [
                    'status' => 'error',
                    'message' => 'User not unblock, unauthorized',
                    
                ]
            ]), 401);
        }

        $userblock = User::find($id);

        if(!$userblock)
        {
            return response(json_encode([
                [
                    'status' => 'error',
                    'message' => 'User not found',
                    
                ]
            ]), 404);
        }

        $userblock->status = 1;
        $userblock->save();

        return response(json_encode([
            [
                'status' => 'success',
                'message' => 'User'.$userblock->name.' unblock with success!',
                
            ]
        ]), 201);

    }
}
