<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasApiTokens;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'phone', 'avatar'];
}
