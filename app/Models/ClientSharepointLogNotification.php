<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSharepointLogNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'Sharepoint_folder_name',
        'emailed',
        'created_at',
        'updated_at'
    ];
}
