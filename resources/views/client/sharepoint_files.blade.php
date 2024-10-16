@extends('layouts.frontapp')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
@endsection
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
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $company_details->investment_company }}
                                </li>
                            </ol>
                        </nav>
                        <div class="row g-4 align-items-end">
                            <div class="col-lg">
                                <h3 class="fs-6 ms-3">Portfolios</h3>
                            </div>
                            <form class="filter" id="filter-form">
                                @csrf
                                <input type="hidden" name="companyid" value="{{ $company_details->id }}">
                                <div class="col-lg-auto" {{$flage==1? "style=display:none" :""}}>
                                    <div class="row align-items-center g-2 mx-3">
                                        <div class="col-auto">
                                            <label for="selectedDateInput">Select Date</label>
                                        </div>
                                        <div class="col">
                                            <div class="input-group date" id="selectDate">
                                                <input type="text" class="form-control border-end-0" name="selectDate" value="" id="selectedDateInput" />
                                                <span class="input-group-addon input-group-text bg-white"><i class="fa-regular fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-secondary btn-block" type="submit">
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <hr class="mb-0" />
                        <ul class="list-group list-group-flush" id="adddata_here">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/scripts/jquery-3.7.1.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('assets/scripts/main.js') }}"></script>

<script>
    $(function() {
        var date = $("input[name=selectDate]").val();
        var csrf_token = $("input[name=_token]").val();
        var cid = $("input[name=companyid]").val();
        var flage = '<?php echo $flage; ?>';
        $.ajax({
            type: "POST",
            url: "{{ route('sharepoint.post') }}",
            data: {
                date: date,
                cid: cid,
                _token: csrf_token,
                flage: flage
            },
            dataType: "json",
            success: function(response) {
                $('#adddata_here').html('')
                if (response.status == true) {
                    $.each(response.data, function(key, val) {
                        //console.log(response.data);
                        //console.log(val.Sharepoint_file_path.replace(/\s+/g,'%20'));
                        $('#adddata_here').append("<button onclick=dwonloadfile('" + val
                            .data_file.replace(/\s+/g, '%20') + "','" + val
                            .Sharepoint_file_path.replace(/\s+/g, '%20') + "','" +
                            response.token +
                            "')  class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>" +
                            val.data_file +
                            "<span class='btn btn-outline-secondary rounded-4'><i class='fa-regular fa-file-pdf'></i></span></button>"
                        );
                    });
                } else {
                    $('#adddata_here').append("<center>No data found</center>");
                }
            }
        });
    });

    $('#filter-form').submit(function(e) {
        e.preventDefault();
        var date = $("input[name=selectDate]").val();
        var csrf_token = $("input[name=_token]").val();
        var cid = $("input[name=companyid]").val();
        $.ajax({
            type: "POST",
            url: "{{ route('sharepoint.post') }}",
            data: {
                date: date,
                cid: cid,
                _token: csrf_token
            },
            dataType: "json",
            success: function(response) {
                $('#adddata_here').html('')
                if (response.status == true) {
                    $.each(response.data, function(key, val) {
                        //console.log(response.data);
                        //console.log(val.Sharepoint_file_path.replace(/\s+/g,'%20'));
                        $('#adddata_here').append("<button onclick=dwonloadfile('" + val
                            .data_file.replace(/\s+/g, '%20') + "','" + val
                            .Sharepoint_file_path.replace(/\s+/g, '%20') + "','" +
                            response.token +
                            "')  class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>" +
                            val.data_file +
                            "<span class='btn btn-outline-secondary rounded-4'><i class='fa-regular fa-file-pdf'></i></span></button>"
                        );
                    });
                } else {
                    $('#adddata_here').append("<center>No data found</center>");
                }
            }
        });
    })

    function dwonloadfile(filename, full_path, token) {
        //console.log("https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFileByServerRelativeUrl('"+file+"')/$value?binaryStringResponseBody=true");
        $.ajax({
            url: "https://douglasinvestmentsza.sharepoint.com/sites/DouglasData/_api/web/GetFileByServerRelativeUrl('" +
                full_path + "')/$value",
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

                // Remove spaces from filename
                var decodedFilename = decodeURIComponent(filename);
                var cleanFilename = decodedFilename.replace(/\s+/g, '_');
                var a = document.createElement('a');
                a.href = "data:application/pdf;base64," + btoa(binary);
                //a.download = cleanFilename + '.pdf';
                a.download = cleanFilename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            },
        });

    }
</script>
@endsection