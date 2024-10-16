<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSharepointSynch extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'user_id',
        'Sharepoint_folder_name',
        'Sharepoint_folder_path',
        'process_flage',
        'flage',
        'created_at',
        'updated_at'
    ];
}
