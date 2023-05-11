<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UserService
{
  /**
   * @var UserRepository
   */
  protected $userRepository;

  /**
   * UserService constructor.
   *
   * @param UserRepository $user
   */
  public function __construct(UserRepository $user)
  {
    $this->userRepository = $user;
  }

  /**
   * Create new user
   * @param Array $data
   * 
   * @return User
   */
  public function saveUserData(Array $data)
  {
    $validator = Validator::make($data, [
      'name' => 'required',
      'email' => 'required|email',
      'address' => 'required',
      'password' => 'required',
      'confirm_password' => 'required|same:password',
    ]);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $result = $this->userRepository->save($data);
    $result['access_token'] = $result->createToken($result->email)->plainTextToken;

    return $result;
  }

  /**
   * Login user
   * @param Array $data
   * 
   * @return User
   */
  public function login(Array $data)
  {
    $validator = Validator::make($data, [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $user = $this->userRepository->findByEmail($data['email']);

    if (! $user || ! Hash::check($data['password'], $user->password)) {
      throw new InvalidArgumentException("The provided credentials are incorrect.");
    }

    $user['access_token'] = $user->createToken($user->email)->plainTextToken;
    return $user;
  }

}