<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }

    public function register(Request $request)
    {
       $validator = Validator::make($request->all(),[
           'name'=>'required',
           'email'=>'required|string|email|unique:users',
           'password'=>'required|confirmed|string|min:6'
       ]);
       
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge(
            $validator -> validated(),
            ['password'=>bcrypt($request->password)]
        ));

        return response()->json([
            'message'=>'User successfully created',
            'user'=>$user,
        ],201);
    }

    public function login(Request $request)
    {
       $validator = Validator::make($request->all(),[
           'email'=>'required|string|email',
           'password'=>'required|min:6'
       ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        if(!$token=auth()->attempt($validator->validated())){
            return response()->json(['error'=>'Unauthorize'],401);
        }
        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'message'=>'User has been login successfully',
            'user'=>auth()->user(),
            'access_token'=> $token,
            'token_type'=> 'bearer',
            'expires_in'=> auth()->factory()->getTTL()*60,
        ]);
    }

    public function profile()
    {
        return response()->json([
            'user'=>auth()->user(),
        ]);
    }

    public function logout()
    {
       auth()->logout();
       return response()->json([
        'message'=>'User has been logout Successfully ',
       ]);
    }
}
