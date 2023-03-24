@extends('frontend.layouts.homepage')

@section('page_main')
    <main>

        <!-- morning_post Area Start -->
        @yield('morning_post')
        <!-- morning_post Area End -->

        <!-- Whats New Start -->
        @yield('new_post')
        <!-- Whats New End -->

        <!--   Weekly2-News start -->
        @yield('weekly2_news')
        <!-- End Weekly-News -->

        <!--  Recent Articles start -->
        @yield('articles')
        <!--Recent Articles End -->

        <!-- Start Video Area -->
        @yield('video_area')
        <!-- End Start Video area-->

        <!--   Weekly3-News start -->
        @yield('weekly3_news')
        <!-- End Weekly-News -->

        <!-- banner-last Start -->
        @yield('banner_last')
        <!-- banner-last End -->

    </main>
@endsection
