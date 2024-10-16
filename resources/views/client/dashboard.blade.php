@extends('layouts.frontapp')
@section('content')
<section class="wrapper">
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Douglas Investments" class="img-fluid" />
            </a>
            <div class="ms-lg-auto navbar-side-section">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <i class="fa-regular fa-circle-user fa-2xl"></i>
                    </div>
                    <div class="col">
                        <p class="mb-0 fw-semibold">Dear {{ Session::get('User')->name }},</p>
                        <small class="mb-0 small">Your dashboard as at {{ $updated_at }}</small>
                    </div>
                    <div class="col-auto ps-5">
                        <div class="row g-3">
                            <a type="button" class="btn btn-outline-light rounded-4" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Log out" id="logout" href="{{ url('/logout') }}">
                                <i class="fa-solid fa-power-off"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </nav>
    <div class="container py-5 h-100">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <div class="profile-card">
                            <a href="profile.html" class="btn btn-outline-primary rounded-4 profile-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Profile"><i class="fa-solid fa-pencil"></i></a>
                            <div class="profile-card-container">
                                <div class="profile-pic">
                                    @if ($manager_data->profile_image != null)
                                    <img src="{{ asset('profile_pics') . '/' . $manager_data->profile_image }}" alt="tag" height="140px" width="200px">
                                    @else
                                    <img src="{{ asset('assets/defualt.jpg') }}" alt="tag" height="140px" width="200px">
                                    @endif
                                </div>
                                <h3 class="fs-3 text-primary mb-0">{{ $manager_data->name }}</h3>
                                <p class="text-secondary fw-semibold mb-0">
                                    Portfolio Manager
                                </p>
                            </div>
                        </div>
                        <div class="p-3">
                            <p>
                                Authorised Financial<br />Services Provider FSP No: 26359<br />
                                Office line: <a href="tel:027 11 463 9102">+27 11 463 9102</a>
                            </p>
                            <div class="contact-block">
                                <a href="tel:027 11 463 9102"><i class="fa-solid fa-phone"></i></a>
                                <a href="mailto:{{ $manager_data->email }}"><i class="fa-solid fa-envelope"></i></a>
                                <a href="https://maps.app.goo.gl/dSZnzFddA54k9egf7" target="_blank"><i class="fa-solid fa-location-dot"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach ($invest_company as $row)
                            @php
                            $invest_company_id = 0;
                             if($row->usercompany!=null){
                                $invest_company_id = $row->usercompany->invest_companie_id;
                             }
                            $file_avalibility = DB::table('client_sharepoint_synche_files')->where('client_code',Session::get('User')->client_code)->where('invest_companie_id',$invest_company_id)->count();
                            @endphp
                            <div class="col-lg-6">
                                <!-- <a href="{{ $row->usercompany ? route('investment_company', $row->id) : '' }}"
                                            class="card {{ !$row->usercompany ? 'disabled' : '' }} h-100 py-2 card-btn"> -->
                                <a href="{{ $file_avalibility!=0 ? route('investment_company', $row->id) : '' }}" class="card {{ $file_avalibility==0? 'disabled' : '' }} h-100 py-2 card-btn">
                                    <div class="card-body">
                                        <div class="row h-100 align-items-center">
                                            <div class="col">
                                                <div class="fs-5 mb-0 fw-semibold text-primary">
                                                    {{ $row->investment_company }}
                                                </div>
                                                <div class="text-xs fw-semibold text-secondary text-uppercase">
                                                    {{ $row->investment_description ? $row->investment_description: '' }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fa-solid fa-chevron-right fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/scripts/main.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection