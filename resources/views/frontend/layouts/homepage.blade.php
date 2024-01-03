@extends('frontend.layouts.base')

@section('body')
    @yield('page_top_head')

    @yield('page_header')

    @yield('page_main')

    @yield('page_footer')
    
    @include('frontend.templates.elements.page_footer')
@endsection

@section('head_css')
    @include('frontend.layouts.head_css')
@endsection

@section('head_js')
    @include('frontend.layouts.head_js')
@endsection

@section('bottom_js')
    @include('frontend.layouts.bottom_js')
@endsection
