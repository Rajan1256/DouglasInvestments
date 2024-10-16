@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.investCompany.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.invest-companies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.investCompany.fields.id') }}
                        </th>
                        <td>
                            {{ $investCompany->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.investCompany.fields.investment_company') }}
                        </th>
                        <td>
                            {{ $investCompany->investment_company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.investCompany.fields.investment_short_code') }}
                        </th>
                        <td>
                            {{ $investCompany->investment_short_code }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.invest-companies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection