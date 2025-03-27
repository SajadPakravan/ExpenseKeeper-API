<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Transactions extends Model
{
    use HasApiTokens;

    protected $table = 'transactions';
    protected $fillable = ['user_id', 'title', 'amount', 'type', 'description', 'created_at'];
    public $timestamps = false;
}
