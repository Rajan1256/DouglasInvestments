@extends('layouts.admin')
@section('styles')
<style>
    table.dataTable>tbody>tr>td.select-checkbox:before {
        display: none;
    }
    .dt-buttons {
        display: none;
    }
</style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
        Client {{$username}} - {{$companyname}} - Year List
            <a class="btn btn-sm btn-danger" href="{{url('/admin/users/folders_level2').'/'.$usercode}}">Back</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                    <thead>
                        <tr>
                            <th>
                                Code
                            </th>
                            <th>
                                Company
                            </th>

                            <th>
                                Year
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($yearfolders as $key => $years)
                                <tr data-entry-id="{{ $years->id }}">
                                    <td>
                                        {{ $years->client_code ?? '' }}
                                    </td>
                                    <td>
                                    {{ $years->investment_company ?? '' }}
                                    </td>

                                    <td>
                                    {{ $years->financial_year ?? '' }}
                                    </td>
                                   
                                    <td>
                                        @if($years->getSharePointSyncCompanyFile)
                                        <a class="btn btn-xs btn-success" href="{{ route('admin.users.company.year.files',[$years->client_code,$years->invest_companie_id,$years->financial_year]) }}">Go to File</a>
                                        @endif
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
            let table = $('.datatable-User:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
