<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientSharepointSyncheYear;

class ClientSharepointSyncheCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'investment_company',
        'invest_companie_id',
        'created_at',
        'updated_at'
    ];

    public function getSharePointSyncCompanyYear()
    {
        return $this->hasOne(ClientSharepointSyncheYear::class,'invest_companie_id','invest_companie_id');
    }

}
