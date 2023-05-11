<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
  /**
   * @var User
   */
  protected $user;

  /**
   * UserRepository constructor.
   *
   * @param User $user
   */
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Create new user
   * @param Array $data
   * 
   * @return User
   */
  public function save(Array $data)
  {
    $user = new $this->user;

    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->address = $data['address'];
    $user->password = bcrypt($data['password']);

    $user->save();

    return $user->fresh();
  }

  /**
   * Find user by email
   * @param string $email
   * 
   * @return User
   */
  public function findByEmail($email)
  {
    return $this->user->where('email', $email)->first();
  }
}