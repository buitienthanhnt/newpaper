@extends('frontend.layouts.pagestruct')

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection


@section('title')
    test knockout
@endsection

@section('main_conten')
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="col-md-12">
                  @yield('content_page')
            </div>
        </div>
    </div>
@endsection
