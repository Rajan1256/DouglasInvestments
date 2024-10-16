@extends('layouts.admin')
@section('styles')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
<style type="text/css">
        .loading {
            z-index: 20;
            position: absolute;
            top: 0;
            left:-5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 40%;
            left:50%;
            animation: spin 2s linear infinite;
            }
              
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
    </style>
@endsection
@section('content')
<section id="loading">
            <div id="loading-content"></div>
</section>

    @can('user_create')
        <div class="row mb-4">
            <div class="col-lg-12 text-end">
                <a class="btn btn-primary" href="{{ route('admin.users.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Client
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Client List
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.user.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.email') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.mobile_no') }}
                            </th>
                            <th>
                                Client Code
                            </th>
                            <th>
                                Manager Name
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            @if (count($user->roles) != 0 ? $user->roles[0]['id'] == 2 : count($user->roles) != 0)
                                <tr data-entry-id="{{ $user->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $user->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->email ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->mobile_no ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->client_code ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->manager->name ?? '' }}
                                    </td>
                                    <td>
                                        @can('user_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.users.show', $user->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('user_edit')
                                            <a class="btn btn-xs btn-secondary"
                                                href="{{ route('admin.users.edit', $user->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('user_delete')
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan
                                            <button class="btn btn-xs btn-warning btn-submit" type="button" onclick="sync({{$user->id}})">Sync</button>
                                            @if($user->getSharePointSyncCompany)
                                            <button class="btn btn-xs btn-info btn-submit" type="button" onclick="send_mail({{$user->id}})">Send Email</button>
                                            <a class="btn btn-xs btn-success" href="{{ route('admin.users.companys', $user->client_code) }}">Visit Files</a>
                                            @endif
                                    </td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    @parent
    <script>
        function sync(id){
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.users.syncUser') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid:id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success==false){
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            // Swal.fire(
                            //         'This user is not avalible in share point right now!',
                            //         'You clicked the button!',
                            //         'success'
                            // );
                            Swal.fire({
                                icon: 'error',
                                title: 'User is not available in SharePoint',
                                text: 'This user is not available in Sharepoint. Please update Sharepoint and click again.',
                            })
                        }else{
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            Swal.fire({
                                title: 'Sync is in Process',
                                text: "Sync will take some time in background.",
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                location.reload();
                              }
                            })
                        } 
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if needed
                        console.error(xhr.responseText);
                    }
                });
        }


        function send_mail(id){
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.users.sendmail') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        userid:id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success==false){
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            // Swal.fire(
                            //         'This user is not avalible in share point right now!',
                            //         'You clicked the button!',
                            //         'success'
                            // );
                            Swal.fire({
                                icon: 'error',
                                title: 'User is not available in SharePoint',
                                text: 'This user is not available in Sharepoint. Please update Sharepoint and click again.',
                            })
                        }else{
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            Swal.fire({
                                title: 'This month data synch mail sent.',
                                text: "",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                location.reload();
                              }
                            })
                        } 
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if needed
                        console.error(xhr.responseText);
                    }
                });
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('user_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.users.massDestroy') }}",
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
