<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Application">
    <meta name="author" content="MoSoft">


    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('backend/img/favicon.ico') }}">

    <title>@yield('title')</title>


    @include('backend.layouts.head-styles')




</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    @include('backend.layouts.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Navbar -->
            @include('backend.layouts.navbar')
            <!-- End of Navbar -->

            <!-- Begin Page Content -->

            @include('backend.layouts.flash')
            @yield('content')
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        @include('backend.layouts.footer')
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->





<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
@include('backend.layouts.model')

<!--   JavaScript-->


@include('backend.layouts.footer-scripts')



</body>

</html>
