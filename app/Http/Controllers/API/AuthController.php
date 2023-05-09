<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Sign in
    function login(Request $request) {
        $request->validate([
				'email' => 'required|email',
				'password' => 'required',
			]);

			$user = User::where('email', $request->email)->first();

			if (! $user || ! Hash::check($request->password, $user->password)) {
				return response()->json([
					'success' => false,
          'message' => "Error Validation",
					'data' => "The provided credentials are incorrect."
				], 400);
			}

			return response()->json([
				'user' => $user,
				'access_token' => $user->createToken($request->email)->plainTextToken
			], 200);
    }

    // Sign Up
    public function signup(Request $request) {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required|email',
				'address' => 'required',
				'password' => 'required',
				'confirm_password' => 'required|same:password',
			]);

			if($validator->fails()){
				return response()->json([
					'success' => false,
          'message' => "Error Validation",
					'data' => $validator->errors()
				], 400); 
			}

			$input = $request->all();
			$input['password'] = bcrypt($input['password']);
			
			$user = User::create($input);
			$success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
			$success['name'] =  $user->name;

			return response()->json([
				'success' => true,
				'data'    => $success,
				'message' => 'User created successfully.',
			], 201);
		}

    // Sign 
    public function logout(Request $request) {
			$request->user()->currentAccessToken()->delete();
			return response()->json([
				'success' => true,
				'data'    => null,
				'message' => 'User created successfully.',
			], 204);
		}

    // Me
    public function getAuthenticatedUser(Request $request) {
			return response()->json([
				'success' => true,
				'data'    => $request->user(),
				'message' => 'User created successfully.',
			], 200);
		}
}
