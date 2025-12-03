<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('backend/img/favicon.ico') }}">

    <title> @yield('title') </title>

    @include('backend.auth.layouts.head-styles')

</head>

<body class="bg-gradient-primary">

@yield('content')

<!-- Bootstrap core JavaScript-->
@include('backend.auth.layouts.footer-script')

</body>

</html>
