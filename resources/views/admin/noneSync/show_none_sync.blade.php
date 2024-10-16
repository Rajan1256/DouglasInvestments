@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Note Sync User List
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
