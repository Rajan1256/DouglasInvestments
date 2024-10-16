<?php

namespace App\Http\Requests;

use App\Models\InvestCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInvestCompanyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('invest_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:invest_companies,id',
        ];
    }
}
