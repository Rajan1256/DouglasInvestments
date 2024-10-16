@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Create Client
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                @if ($errors->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->first('password') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.sa_id') }}</label>
                <input class="form-control {{ $errors->has('sa_id') ? 'is-invalid' : '' }}" type="text" name="sa_id" id="sa_id" value="{{ old('sa_id', '') }}" required>
                @if ($errors->has('sa_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('sa_id') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.sa_id_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="known_as">{{ trans('cruds.user.fields.known_as') }}</label>
                <input class="form-control {{ $errors->has('known_as') ? 'is-invalid' : '' }}" type="text" name="known_as" id="known_as" value="{{ old('known_as', '') }}">
                @if ($errors->has('known_as'))
                <div class="invalid-feedback">
                    {{ $errors->first('known_as') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.known_as_helper') }}</span>
            </div>


            <div class="form-group">
                <label class="required">{{ trans('cruds.user.fields.gender') }}</label>
                <select class="form-select {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender" required>
                    <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>
                        {{ trans('global.pleaseSelect') }}
                    </option>
                    @foreach (App\Models\User::GENDER_SELECT as $key => $label)
                    <option value="{{ $key }}" {{ old('gender', 'Male') === (string) $key ? 'selected' : '' }}>{{ $label }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('gender'))
                <div class="invalid-feedback">
                    {{ $errors->first('gender') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.gender_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="profile_images">Profile Image</label>
                <input class="form-control {{ $errors->has('profile_images') ? 'is-invalid' : '' }}" type="file" name="profile_images" id="profile_images" value="{{ old('profile_images', '') }}">
                @if ($errors->has('profile_images'))
                <div class="invalid-feedback">
                    {{ $errors->first('profile_images') }}
                </div>
                @endif
            </div>
            <div class="form-group">
                <label for="mobile_no">{{ trans('cruds.user.fields.mobile_no') }}</label>
                <input class="form-control {{ $errors->has('mobile_no') ? 'is-invalid' : '' }}" type="text" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', '') }}">
                @if ($errors->has('mobile_no'))
                <div class="invalid-feedback">
                    {{ $errors->first('mobile_no') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.mobile_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.user.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address') }}</textarea>
                @if ($errors->has('address'))
                <div class="invalid-feedback">
                    {{ $errors->first('address') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="client_code" class="required">{{ trans('cruds.user.fields.client_code') }}</label>
                <input class="form-control {{ $errors->has('client_code') ? 'is-invalid' : '' }}" type="text" name="client_code" id="client_code" value="{{ old('client_code', '') }}" required>
                @if ($errors->has('client_code'))
                <div class="invalid-feedback">
                    {{ $errors->first('client_code') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.client_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="manager_id">{{ trans('cruds.user.fields.manager') }}</label>
                <select class="form-select select2 {{ $errors->has('manager') ? 'is-invalid' : '' }}" name="manager_id" id="manager_id" required>
                    @foreach ($managers as $id => $entry)
                    <option value="{{ $id }}" {{ old('manager_id') == $id ? 'selected' : '' }}>
                        {{ $entry }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('manager'))
                <div class="invalid-feedback">
                    {{ $errors->first('manager') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.manager_helper') }}</span>
            </div>

            <div class="form-group">
            <label for="email_list">Additional Email List</label>
                <div class="row" id="inputFormRow">
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                            <input type="text" data-aemail-target="#aemail" id="aname" name="a_name[]" class="form-control m-input adjustment-target" placeholder="Enter Name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                            <input type="email" data-aname-target="#aname" id="aemail" name="a_email[]" class="form-control m-input adjustment-target-email" placeholder="Enter Email" autocomplete="off">
                            <div class="input-group-append">
                                <button id="addRow" type="button" class="btn btn-secondary">Add Row</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="newRow"></div>
            </div>
            
            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">

$('.adjustment-target').bind('change', function() {
        $adjustment = $(this);
        $comment = $($adjustment.data('aemail-target'));
        adjustment_value = $adjustment.val();
        console.log(adjustment_value.length);
        if (adjustment_value.length == 0) {
            $comment.prop('required', false);
        } else {
            $comment.prop('required', true);
        }
    });

    $('.adjustment-target-email').bind('change', function() {
        $adjustment_email = $(this);
        $comment = $($adjustment_email.data('aname-target'));
        adjustment_value = $adjustment_email.val();
        console.log(adjustment_value.length);
        if (adjustment_value.length == 0) {
            $comment.prop('required', false);
        } else {
            $comment.prop('required', true);
        }
    });
    // add row
    $("#addRow").click(function() {
        var html = '';
        html += '<div class="row" id="inputFormRow">';
        html += '<div class="col-lg-6">';
        html += '<div class="input-group mb-3">';
        html +=
            '<input type="text" name="a_name[]" class="form-control m-input" placeholder="Enter Name" autocomplete="off" required>';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-lg-6">';
        html += '<div class="input-group mb-3">';
        html +=
            '<input type="email" name="a_email[]" class="form-control m-input" placeholder="Enter Email" autocomplete="off" required>';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $('#newRow').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function() {
        $(this).closest('#inputFormRow').remove();
    });
</script>
@endsection