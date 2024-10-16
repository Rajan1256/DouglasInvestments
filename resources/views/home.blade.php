@extends('layouts.admin')
<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }

    .small-box:hover {
        text-decoration: none;
    }

    .small-box>.inner {
        padding: 10px;
        color: #fff;
    }

    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px;
        padding: 0;
        white-space: nowrap;
    }

    .small-box p {
        font-size: 1rem;
    }

    .small-box .icon>i {
        font-size: 70px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: -webkit-transform .3s linear;
        transition: transform .3s linear;
        transition: transform .3s linear, -webkit-transform .3s linear;
    }
</style>
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <!-- <div class="card-body">
                    @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    You are logged in!
                </div> -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-6">

            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$users}}</h3>
                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-6">

            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$portfolio_manager}}</h3>
                    <p>Portfolio Manager</p>
                </div>
                <div class="icon">
                    <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                </div>
            </div>
        </div>

        <!-- <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>44</h3>
                    <p>User Registrations</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>
                    <p>Unique Visitors</p>
                </div>
            </div>
        </div> -->

    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection