@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @render(\App\ViewBlock\TopBar::class)
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection

@section('page_title') Trang chủ @endsection

@section('morning_post')
    @include('frontend/layouts/paperComponent/morning_post')
@endsection

@section('new_post')
    @include('frontend/layouts/paperComponent/new_post')
@endsection

@section('weekly2_news')@endsection

@section('articles')@endsection

@section('video_area')
    @include('frontend/layouts/paperComponent/video_area')
@endsection

@section('banner_last')
    @include('frontend/layouts/paperComponent/banner_last')
@endsection

@section('weekly3_news')@endsection
