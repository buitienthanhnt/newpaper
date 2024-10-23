<!doctype html>
<html lang="zxx">
    <head>
        <title>@yield('page_title')</title>
        <meta name="description" content="">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('metas')
        <link rel="shortcut icon" type="image/x-icon" href={{ asset('assets/frontend/img/favicon.ico') }}>
        @yield('head_css')
        @yield('head_js')
        <script>
            var baseUrl = "{{route('/')}}"
        </script>
    </head>
    <body>
        @include('sweetalert::alert')
        <!-- Preloader Start -->
        @yield('body')
        <!-- Search model end -->
        @yield('bottom_js')
    </body>
</html>
