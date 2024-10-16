<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSharepointSyncheFileLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'investment_company',
        'financial_year',
        'data_file',
        'file_date',
        'Sharepoint_file_path',
        'invest_companie_id',
        'created_at',
        'updated_at'
    ];
}
