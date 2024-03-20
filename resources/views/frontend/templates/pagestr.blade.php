@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection

{{-- ========base============ --}}

{{-- page_title --}}
@section('page_title')
    home demo
@endsection

{{-- morning_post --}}
@section('morning_post')
    <div class="trending-area fix pt-25 gray-bg">
        <div class="container">
            <div class="trending-main">
                <div class="row">

                    <div class="col-lg-12">
                        @yield('full_content')
                    </div>

                    <!-- Trending Top -->
                    <div class="col-lg-8">
                        @yield('trending_left')
                    </div>
                    <!-- Trending Top -->

                    <!-- Right content -->
                    <div class="col-lg-4">
                        <div class="row">
                            @yield('trending_right')
                        </div>
                    </div>
                    <!-- Right content -->

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- new_post --}}
@section('new_post')
    <section class="whats-news-area pt-50 pb-20 gray-bg">
        <div class="container">
            <div class="row">

                {{-- new_post left --}}
                <div class="col-lg-8">

                    @yield('new_post_left')

                    <!-- Banner -->
                    @yield('new_post_banner')
                    <!-- Banner -->

                </div>
                {{-- new_post left --}}

                {{-- new_post right --}}
                <div class="col-lg-4">

                    <!-- Flow Socail -->
                    @yield('flow_socail')
                    <!-- Flow Socail -->

                    <!-- Most Recent Area -->
                    @yield('most_recent')
                    <!-- Most Recent Area -->
                </div>
                {{-- new_post right --}}

            </div>
        </div>
    </section>
@endsection

{{-- weekly2_news --}}
@section('weekly2_news')
    <div class="weekly2-news-area pt-50 pb-30 gray-bg">
        <div class="container">
            <div class="weekly2-wrapper">
                <div class="row">

                    <!-- Banner -->
                    <div class="col-lg-3">
                        @yield('weekly2_banner')
                    </div>
                    <!-- Banner -->

                    {{-- weekly2_conten --}}
                    <div class="col-lg-9">
                        @yield('weekly2_conten')
                    </div>
                    {{-- weekly2_conten --}}

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- articles --}}
@section('articles')
    <div class="recent-articles pt-40 pb-40">
        <div class="container">
            <div class="recent-wrapper">

                <!-- Trending section Tittle -->
                <div class="row">
                    @yield('articles_title')
                </div>
                <!-- section Tittle -->

                {{-- Trending articles_conten --}}
                <div class="row">
                    @yield('articles_conten')
                </div>
                {{-- articles_conten --}}

            </div>
        </div>
    </div>
@endsection

{{-- video_area --}}
@section('video_area')
    <div class="youtube-area video-padding d-none d-sm-block">

        <div class="container">
            {{-- video_area --}}
            @yield('video_area_conten')
            {{-- video_area --}}
        </div>

    </div>
@endsection

{{-- weekly3_news --}}
@section('weekly3_news')
    <div class="weekly3-news-area pt-80 pb-130">
        <div class="container">
            <div class="weekly3-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="slider-wrapper">

                            <!-- Slider -->
                            <div class="row">
                                <div class="col-lg-12">

                                    {{-- weekly3_conten --}}
                                    @yield('weekly3_conten')
                                    {{-- weekly3_conten --}}

                                </div>
                            </div>
                            <!-- Slider -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- banner_last --}}
@section('banner_last')
    <div class="banner-area gray-bg pt-90 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                @yield('banner_last_conten')
            </div>
        </div>
    </div>
@endsection
