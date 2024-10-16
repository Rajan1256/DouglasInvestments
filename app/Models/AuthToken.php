<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'sharepoint_auth_token',
        'created_at',
        'updated_at'
    ];
}
