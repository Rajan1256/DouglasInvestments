@extends('layouts.frontapp')
@section('content')
<section class="login-bg">
    <div class="px-4 py-5 px-md-5 text-center text-lg-start w-100">
        <div class="container">
            <div class="row gx-lg-5 align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 d-none d-lg-block">
                    <div class="info-strip">
                        <h1 class="mb-5 display-5 fw-bold ls-tight text-secondary text-shadow">
                        Innovative<br />
                            <span class="text-primary">Investing </span>
                        </h1>
                        <p>
                        A holistic approach to investing for Private Clients, focusing on long-term relationships and a personalized customer service.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-md-5 mx-md-4">
                            <div class="text-center">
                                <img src="{{ asset('assets/images/logo-color.png')}}" class="img-fluid mb-4 w-100 login-logo" alt="Douglas Investments" />
                            </div>

                            <form class="login" id="login-form">
                                <p>Please login to your account</p>
                                <div id="errors-list"></div>
                                @csrf
                                <div class="form-floating mb-4">
                                    <input type="email" name="email" id="email" class="form-control" id="floatingInput" placeholder="name@example.com" required />
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="input-group mb-4" id="showHidePassword">
                                    <div class="form-floating">
                                        <input type="password" name="password" id="password" class="form-control" id="floatingPassword" placeholder="Password" required />
                                        <label for="floatingPassword">Password</label>
                                    </div>
                                    <span class="input-group-text password-btn"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                </div>

                                <div class="text-center mb-4">
                                    <input class="btn btn-primary btn-block px-5" type="submit" value="Log in">
                                </div>
                                <div class="text-center">
                                    Need help?
                                    <a href="contact.html" class="link">Contact us</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')

<script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.js')}}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/scripts/main.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $('#login-form').submit(function(e) {

        e.preventDefault();

        var email = $("input[name=email]").val();
        var password = $("input[name=password]").val();
        var csrf_token = $("input[name=_token]").val();

        $.ajax({
            type: "POST",
            url: "{{ route('userlogin.post') }}",
            data: {
                email: email,
                password: password,
                _token: csrf_token
            },
            dataType: "json",
            success: function(response) {
                if (response.status == false) {
                    $('#errors-list').html('')
                    $.each(response.errors, function(key, val) {
                        $("#errors-list").append("<div class='alert alert-danger'>" + val + "</div>");
                    });
                } else {
                    window.location = response.redirect;
                }
            }
        });
    })
</script>
@endsection