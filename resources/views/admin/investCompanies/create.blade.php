@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.investCompany.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.invest-companies.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required"
                        for="investment_company">{{ trans('cruds.investCompany.fields.investment_company') }}</label>
                    <input class="form-control {{ $errors->has('investment_company') ? 'is-invalid' : '' }}" type="text"
                        name="investment_company" id="investment_company" value="{{ old('investment_company', '') }}"
                        required>
                    @if ($errors->has('investment_company'))
                        <div class="invalid-feedback">
                            {{ $errors->first('investment_company') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.investCompany.fields.investment_company_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required"
                        for="investment_short_code">{{ trans('cruds.investCompany.fields.investment_short_code') }}</label>
                    <input class="form-control {{ $errors->has('investment_short_code') ? 'is-invalid' : '' }}"
                        type="text" name="investment_short_code" id="investment_short_code"
                        value="{{ old('investment_short_code', '') }}" required>
                    @if ($errors->has('investment_short_code'))
                        <div class="invalid-feedback">
                            {{ $errors->first('investment_short_code') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.investCompany.fields.investment_short_code_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class=""
                        for="investment_description">{{ trans('cruds.investCompany.fields.investment_description') }}</label>
                    <input class="form-control {{ $errors->has('investment_description') ? 'is-invalid' : '' }}"
                        type="text" name="investment_description" id="investment_description"
                        value="{{ old('investment_description', '') }}" >
                    @if ($errors->has('investment_description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('investment_description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.investCompany.fields.investment_description_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
