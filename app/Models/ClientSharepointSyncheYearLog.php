<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSharepointSyncheYearLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'investment_company',
        'financial_year',
        'invest_companie_id',
        'created_at',
        'updated_at'
    ];
}
