<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendTestMailToAllUser extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_name',
        'user_email',
        'user_id',
        'emailed',
        'replyto_name',
        'replyto_email',
        'created_at',
        'updated_at'
    ];

}
