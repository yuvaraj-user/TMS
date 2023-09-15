<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="mazenet task management" name="description" />
    <meta content="developer" name="author" />
    <title>Mazenet</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ URL::to('public/build/assets/app-3ea8b221.css') }}" rel="stylesheet">
    <script src="{{ URL::to('public/build/assets/app-7e506d02.js') }}"></script>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{  asset('images/favicon.ico') }}">

</head>

<body>
    <div id="app">
        @yield('content')
    </div>
</body>

</html>