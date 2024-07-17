<!doctype html>
<html lang="zxx">
<head>
    <title>@yield('page_title')</title>
    @yield('meta_title')
    <meta name="description" content="">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="manifest" href="site.webmanifest"> --}}
    <link rel="shortcut icon" type="image/x-icon" href={{ asset('assets/frontend/img/favicon.ico') }}>
    @yield('head_css')
    @yield('head_js')
</head>
<body>
@include('sweetalert::alert')

<!-- Preloader Start -->
@yield('body')

<!-- Search model end -->
@yield('bottom_js')
</body>
</html>
