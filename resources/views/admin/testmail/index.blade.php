@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Send a Test Mail
    </div>

    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <form method="POST" action="{{ route('admin.users.sendtestmail') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">User</label>
                <select class="form-select" name="userid" id="userid" required>
                    <option value="">---select---</option>
                    @foreach($users as $user)
                    @if($user->getSharePointSyncCompany!=null)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endif
                    @endforeach
                </select>

                <span class="help-block">{{ trans('cruds.user.fields.gender_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="test_email">Email</label>
                <input type="email" class="form-control" name="test_email" id="test_email" required>
            </div>


            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    Send
                </button>
            </div>
        </form>
    </div>
</div>
@endsection