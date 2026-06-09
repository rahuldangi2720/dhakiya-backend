<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(){
    $users = User::where(
        'id',
        '!=',
        auth('api')->id()
    )->get();
  return UserResource::collection($users);

  }
}
  // if any user login with its account then its account not showing in list only other users show its sccount