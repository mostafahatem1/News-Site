<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $setting->site_name }}| @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Bootstrap News Template - Free HTML Templates" name="keywords" />
    <meta content="Bootstrap News Template - Free HTML Templates" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('/'. $setting->favicon ) }}" rel="icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet" />
    @include('frontend.layouts.style')
</head>

<body>
    <!-- Top Bar Start -->
    @include('frontend.layouts.nav')
    <!-- Nav Bar End -->
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap ">
        <div class="container">
            <ul class="breadcrumb bg-dark" >
                @section('breadcrumb')
               {{-- empty --}}
                @show
            </ul>
        </div>
    </div>
    <!-- Breadcrumb End -->

    @yield("content")

    <!-- Footer Start -->
    @include('frontend.layouts.footer')
    <!-- Footer Bottom End -->

    <!-- Back to Top -->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    @include('frontend.layouts.script')
</body>

</html>
