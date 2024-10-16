<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'a_name',
        'a_email',
        'created_at',
        'updated_at'
    ];
}
