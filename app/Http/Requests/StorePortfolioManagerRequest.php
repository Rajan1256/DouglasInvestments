<?php

namespace App\Http\Requests;

use App\Models\portfolioManager;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePortfolioManagerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:portfolio_managers',
            ],
            'password' => [
                'required',
            ],
           
            'gender' => [
                'required',
            ],
            'mobile_no' => [
                'string',
                'nullable',
            ],
            'profile_images' => [
                'mimes:png,jpg,jpeg|max:2048'
            ],
            'client_code' => [
                'string',
                'nullable',
                'unique:portfolio_managers'
            ],
            'sa_id' => [
                'string',
                'nullable',
                'unique:portfolio_managers'
            ]
        ];
    }

    public function messages()
    {
        return [
            'client_code.unique' => 'Code should be unique'
        ];
    }
}
