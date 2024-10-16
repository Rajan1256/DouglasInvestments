@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Edit Admin
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.admins.update', [$user->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', $user->name) }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email', $user->email) }}" required>
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                        name="password" id="password">
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="known_as">{{ trans('cruds.user.fields.known_as') }}</label>
                    <input class="form-control {{ $errors->has('known_as') ? 'is-invalid' : '' }}" type="text"
                        name="known_as" id="known_as" value="{{ old('known_as', $user->known_as) }}">
                    @if ($errors->has('known_as'))
                        <div class="invalid-feedback">
                            {{ $errors->first('known_as') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.known_as_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required">{{ trans('cruds.user.fields.gender') }}</label>
                    <select class="form-select {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender"
                        id="gender" required>
                        <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\User::GENDER_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('gender', $user->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}
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
                    <label for="mobile_no">{{ trans('cruds.user.fields.mobile_no') }}</label>
                    <input class="form-control {{ $errors->has('mobile_no') ? 'is-invalid' : '' }}" type="text"
                        name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $user->mobile_no) }}">
                    @if ($errors->has('mobile_no'))
                        <div class="invalid-feedback">
                            {{ $errors->first('mobile_no') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.mobile_no_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="address">{{ trans('cruds.user.fields.address') }}</label>
                    <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', $user->address) }}</textarea>
                    @if ($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.address_helper') }}</span>
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
