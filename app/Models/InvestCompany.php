<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClientSharepointSyncheCompany;

class InvestCompany extends Model
{
    use HasFactory;

    public $table = 'invest_companies';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'investment_company',
        'investment_short_code',
        'investment_description',
       
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function userCompany()
    {
        return $this->hasOne(ClientSharepointSyncheCompany::class,'invest_companie_id','id');
    }
}
