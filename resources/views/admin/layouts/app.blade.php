<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="mazenet task management" name="description" />
    <meta content="developer" name="author" />
    <title>Task Management</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{  URL::to('public/images/favicon.ico') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{URL::to('public/assets/libs/jquery/jquery.min.js')}}"></script>

    <!-- DataTables -->
    <link href="{{URL::to('public/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('public/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{URL::to('public/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />



    <link href="{{URL::to('public/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('public/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('public/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{URL::to('public/assets/css/custom.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- 
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    

</head>

<body>
    @include('admin.layouts.navbar')

    <!-- ========== Left Sidebar Start ========== -->
    @include('admin.layouts.sidebar')
    <!-- Left Sidebar End -->

    <!-- start Page-content -->
    <div class="main-content mt-5">
        @yield('content')
        @include('admin.layouts.footer')
    </div>
    
    <!-- end main content-->

    </div>


    <!-- <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script> -->

    <script src="{{URL::to('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{URL::to('public/assets/js/pages/dashboard.init.js')}}"></script>
    <script src="{{URL::to('public/assets/js/app.js')}}"></script>
    <!-- apexcharts -->
    <!-- <script src="assets/libs/apexcharts/apexcharts.min.js"></script> -->

    <!-- dashboard init -->
    <!-- Required datatable js -->
    <script src="{{URL::to('public/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{URL::to('public/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::to('public/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- Datatable init js -->
    <!-- <script src="{{URL::to('public/assets/js/pages/datatables.init.js')}}"></script> -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

</body>
<script>
    $(function() {
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('.select-box').select2({
            width: '100%'
        });

    });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 1000);

    setTimeout(function() {
        $('.validation-error').fadeOut();
    }, 5000);

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>

</html>