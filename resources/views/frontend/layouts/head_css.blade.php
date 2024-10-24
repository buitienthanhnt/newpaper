<!-- CSS here src=asset(' -->
@yield('css_before')
<link rel="stylesheet" href={{ asset('assets/frontend/css/bootstrap.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/owl.carousel.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/ticker-style.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/flaticon.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/slicknav.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/animate.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/magnific-popup.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/fontawesome-all.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/themify-icons.css') }}>
<link rel="stylesheet" href={{ asset('assets/frontend/css/slick.css') }}>
{{-- <link rel="stylesheet" href={{asset('assets/frontend/css/nice-select.css')}}> --}}
<link rel="stylesheet" href={{ asset('assets/frontend/css/style.css') }}>
<style>
</style>
@isset($custom_css)
    <style>
        {{ $custom_css }} .text-2lines {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .text-3lines {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    </style>
@endisset
@yield('css_after')
