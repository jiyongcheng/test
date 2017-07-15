<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{
    public function signup(Request $request)
    {
    	$this->validate($request,[
    		'username' => 'required',
    		'email' => 'required|email|unique:users',
    		'password' => 'required'
    	]);
    	$user = new User();
    	$user->name = $request->input('username');
    	$user->email = $request->input('email');
    	$user->password = bcrypt($request->input('password'));
    	$user->save();
    	return response()->json([

				'message' => 'successfully created user!'
    		],201);
    }

    public function signin(Request $request) 
    {
    	$this->validate($request,[
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

        $crediential = $request->only('email','password');
    	try {
    		if (!$token = JWTAuth::attempt($crediential)) {
    			return response()->json(['error'=>'invalid crediential!'],401);
    		}


    	}catch(JWTException $e) {
			return response()->json(['error'=>'could not create token!'],500);
    	}

    	return response()->json(['token'=>$token],200);
    }
}
