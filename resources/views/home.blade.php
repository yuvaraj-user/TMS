<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>Mazenet</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="mazenet task management" name="description" />
        <meta content="developer" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->

        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('layouts.navbar')
            
           

            <!-- ========== Left Sidebar Start ========== -->
            @include('layouts.sidebar')
            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
           
           
            <!-- start Page-content -->
            @yield('content')
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        @include('layouts.page_settings')
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <!-- <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script> -->
        <!-- <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script> -->

        <!-- apexcharts -->
        <!-- <script src="assets/libs/apexcharts/apexcharts.min.js"></script> -->

        <!-- dashboard init -->
        <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/app.js')}}"></script>
    </body>


<!-- Mirrored from themesbrand.com/skote/layouts/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 22 Sep 2021 11:04:47 GMT -->
</html>