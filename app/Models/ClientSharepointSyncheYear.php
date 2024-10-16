<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientSharepointSyncheFile;
class ClientSharepointSyncheYear extends Model
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

    public function getSharePointSyncCompanyFile()
    {
        return $this->hasOne(ClientSharepointSyncheFile::class,'invest_companie_id','invest_companie_id');
    }
}
