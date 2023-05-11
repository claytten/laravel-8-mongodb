<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
	function login(Request $request) 
	{
		$data = $request->all();

		try {
			$data = $this->userService->login($data);
			return $this->sendResponse($data, 'Login success');
		} catch(Exception $e) {
			$this->sendError($e->getMessage(), [], $e->getCode());
		}
	}

	/**
	 * Signup
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function signup(Request $request) 
	{
		$data = $request->all();

		try {
			$data = $this->userService->saveUserData($data);
			return $this->sendResponse($data, 'User created successfully.', Response::HTTP_CREATED);
		} catch(Exception $e) {
			$this->sendError($e->getMessage(), [], $e->getCode());
		}
	}

	/**
	 * Logout
	 * @param Request $request
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request) {
		$request->user()->currentAccessToken()->delete();
		return response()->json([
			'success' => true,
			'data'    => null,
			'message' => 'User created successfully.',
		], 204);
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
