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
        Client {{$username}} - {{$companyname}} - {{$yearid}} - Files List
        <a class="btn btn-sm btn-danger" href="{{ URL::previous() }}">Back</a>
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
                            Month & Year
                        </th>
                        <th>
                            Files
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($folder_files as $key => $files)
                    <tr data-entry-id="{{ $files->id }}">
                        <td>
                            {{ $files->client_code ?? '' }}
                        </td>
                        <td>
                            {{ $files->investment_company ?? '' }}
                        </td>
                        <td>
                            {{ $files->file_date ?? '' }}
                        </td>
                        <td>
                            {{ $files->data_file ?? '' }}
                        </td>

                        <td>

                            <button class="btn btn-xs btn-success"  onclick="dwonloadfile('{{$files->data_file}}','{{$files->Sharepoint_file_path}}','{{$token}}')">Open File</button>
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
            // orderCellsTop: true,
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


    function dwonloadfile(filename,full_path,token) {
        //console.log("https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFileByServerRelativeUrl('"+file+"')/$value?binaryStringResponseBody=true");
        $.ajax({
            url: "https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFileByServerRelativeUrl('"+full_path.replace(/\s+/g, '%20')+"')/$value",
            type: 'get',
            contentType: true,
            processData: false,
            encoding: null,
            headers: {
                accept: 'application/json; odata=verbose',
                "Authorization": "Bearer " + token
            },
            beforeSend: function(request) {
                request.overrideMimeType('text/plain; charset=x-user-defined');
            },
            success: function(response) {
                var binary = "";
                var responseTextLen = response.length;

                for (i = 0; i < responseTextLen; i++) {
                    binary += String.fromCharCode(response.charCodeAt(i) & 255)
                }
                var a = document.createElement('a');
                a.href = "data:application/pdf;base64," + btoa(binary);
                a.download = filename+'.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
            },
        });

    }
</script>
@endsection