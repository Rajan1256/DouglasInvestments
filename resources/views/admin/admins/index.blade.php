@extends('layouts.admin')
@section('content')
    @can('user_create')
        <div class="row mb-4">
            <div class="col-lg-12 text-end">
                <a class="btn btn-primary" href="{{ route('admin.admins.create') }}">
                    <i class="fa-solid fa-plus"></i> Add Admin
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            Admin List
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
                                {{ trans('cruds.user.fields.email_verified_at') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.roles') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.known_as') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.gender') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.mobile_no') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.address') }}
                            </th>

                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            @if (count($user->roles) != 0 ? $user->roles[0]['id'] == 3 : count($user->roles) != 0)
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
                                        {{ $user->email_verified_at ?? '' }}
                                    </td>
                                    <td>
                                        @foreach ($user->roles as $key => $item)
                                            <span class="badge badge-info">{{ $item->title }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $user->known_as ?? '' }}
                                    </td>
                                    <td>
                                        {{ App\Models\User::GENDER_SELECT[$user->gender] ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->mobile_no ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->address ?? '' }}
                                    </td>
                                    <td>
                                        @can('user_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="{{ route('admin.admins.show', $user->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('user_edit')
                                            <a class="btn btn-xs btn-secondary"
                                                href="{{ route('admin.admins.edit', $user->id) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('user_delete')
                                            <form action="{{ route('admin.admins.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                        @endcan

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
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('user_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.admins.massDestroy') }}",
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
