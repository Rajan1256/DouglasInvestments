@extends('layouts.admin')
@section('content')
    @can('invest_company_create')
        <div class="row mb-4">
            <div class="col-lg-12 text-end">
                <a class="btn btn-primary" href="{{ route('admin.invest-companies.create') }}">
                    <i class="fa-solid fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.investCompany.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.investCompany.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-InvestCompany">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.investCompany.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.investCompany.fields.investment_company') }}
                            </th>
                            <th>
                                Investment Description
                            </th>
                            <th>
                                {{ trans('cruds.investCompany.fields.investment_short_code') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($investCompanies as $key => $investCompany)
                            <tr data-entry-id="{{ $investCompany->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $investCompany->id ?? '' }}
                                </td>
                                <td>
                                    {{ $investCompany->investment_company ?? '' }}
                                </td>
                                <td>
                                    {{ $investCompany->investment_short_code ?? '' }}
                                </td>
                                <td>
                                    {{ $investCompany->investment_description ?? '' }}
                                </td>
                                <td>
                                    @can('invest_company_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.invest-companies.show', $investCompany->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('invest_company_edit')
                                        <a class="btn btn-xs btn-secondary"
                                            href="{{ route('admin.invest-companies.edit', $investCompany->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('invest_company_delete')
                                        <form action="{{ route('admin.invest-companies.destroy', $investCompany->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('invest_company_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.invest-companies.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-InvestCompany:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
