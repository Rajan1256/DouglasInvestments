<!DOCTYPE html>
<html data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Douglas Investments</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/apple-icon-57x57.png')}}" />
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/apple-icon-60x60.png')}}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/apple-icon-72x72.png')}}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/apple-icon-76x76.png')}}" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/apple-icon-114x114.png')}}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/apple-icon-120x120.png')}}" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/apple-icon-144x144.png')}}" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/apple-icon-152x152.png')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-icon-180x180.png')}}" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/android-icon-192x192.png')}}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png')}}" />
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/favicon-96x96.png')}}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png')}}" />
    <link rel="manifest" href="{{ asset('assets/images/manifest.json')}}" />
    <meta name="msapplication-TileColor" content="#005a82" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/ms-icon-144x144.png')}}" />
    <meta name="theme-color" content="#005a82" />
    <link href="{{ asset('assets/vendors/bootstrap/css/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous" />

    <link href="{{ asset('assets/vendors/fontawesome/css/all.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" />
    @yield('styles')
</head>

@yield('content')

@yield('scripts')
</body>

</html>