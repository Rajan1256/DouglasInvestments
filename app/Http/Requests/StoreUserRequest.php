<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
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
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'known_as' => [
                'string',
                'nullable',
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
                'unique:users'
            ],
            'sa_id' => [
                'string',
                'nullable',
                'unique:users'
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
