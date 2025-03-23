<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersAuth extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'users_auth';
    protected $fillable = ['user_id', 'username', 'password'];
}
