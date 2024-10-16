<?php

namespace App\Http\Requests;

use App\Models\InvestCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInvestCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('invest_company_edit');
    }

    public function rules()
    {
        return [
            'investment_company' => [
                'string',
                'required',
            ],
            'investment_short_code' => [
                'string',
                'required',
            ],
        ];
    }
}
