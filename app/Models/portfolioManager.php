<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class portfolioManager extends Model
{
    use HasFactory;
    public const GENDER_SELECT = [
        'Male'   => 'Male',
        'Female' => 'Female',
    ];
    protected $fillable = 
    ['name', 'email', 'address','gender','mobile_no','client_code','sa_id','profile_image'];

    
}
