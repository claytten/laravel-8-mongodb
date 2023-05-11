<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Http\Requests\Users\RegisterRequest;
use App\Services\UserService;
use App\Traits\ResponseApiTrait;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	use ResponseApiTrait;

	public function __construct(
		protected UserService $userService,
	)
	{
		
	}

	/**
	 * Login
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	function login(LoginRequest $request) 
	{
		$data = $this->userService->login($request->all());
		return $this->sendResponse($data, 'Login success');
	}

	/**
	 * Signup
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function signup(RegisterRequest $request) 
	{
		$data = $this->userService->saveUserData($request->all());
		return $this->sendResponse($data, 'User created successfully.', Response::HTTP_CREATED);
	}

	/**
	 * Logout
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request) {
		$request->user()->currentAccessToken()->delete();
		return $this->sendResponse(null, 'User logout successfully.', Response::HTTP_NO_CONTENT);
	}

	/**
	 * Get authenticated user
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAuthenticatedUser(Request $request) {
		return $this->sendResponse($request->user(), 'User retrieved successfully.');
	}
}
