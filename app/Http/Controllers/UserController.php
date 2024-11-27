<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginUserRequest;
//use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function register(Request $request){
       $request->validate([
            'username'=>'required|string|max:255',
            'phone'=>'required|string|min:10',
            'password'=>'required|string|min:8|confirmed'
        ]);
       $user= User::create([
             'username'=>$request->username,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password)
        ]);
         return response()->json([
           'message'=>'User Registered Successfully',
           'User'=>$user,
            201]);
    }

    public function login(Request $request){
        $request->validate([
            'phone'=>'required|string|min:10',
            'password'=>'required|string|min:8'
        ]);

            if(!Auth::attempt($request->only('phone','password')))
            return response()->json([
                'message'=>'invalid phone'],401);
       $user= User::where('phone',$request->phone)->FirstOrFail();
       $token= $user->createToken('auth_Token')->plainTextToken;
          return response()->json([
            'message'=>'Login Successfully',
            'User'=>$user,
            'Token'=>$token]
            ,201);
    }
    public function logout(Request $request){
     $request->user()->currentAccessToken()->delete();
     return response()->json([
        'message'=>'Logout Successful'],201);

    }

}
