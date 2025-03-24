<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UsersVerify extends Model
{
    use HasApiTokens;
    protected $table = 'users_verify';
    protected $fillable = ['data', 'code'];
}
