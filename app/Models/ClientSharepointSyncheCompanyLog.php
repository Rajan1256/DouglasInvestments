<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSharepointSyncheCompanyLog extends Model
{
    use HasFactory;

    protected $table = 'client_sharepoint_synche_companie_logs';

    protected $fillable = [
        'client_code',
        'investment_company',
        'invest_companie_id',
        'created_at',
        'updated_at'
    ];
}
