@extends('frontend/layouts/category_view')
@inject('DomHtml', 'App\Helper\HelperFunction')

@section('page_title')
{{ $tag }}
@endsection

{{-- col-lg-8 --}}
@section('trending_left')
<div class="slider-active">
    @if ($trending_left)
    @foreach ($trending_left as $tren)
    <div class="single-slider">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src="{{ $tren->getImagePath() }}" alt="">
                <div class="trend-top-cap">
                    @if ($first_category = $tren->to_category()->first())
                    <a href="">
                        <span class="bgr" data-animation="fadeInUp" data-delay=".2s" data-duration="1000ms">{{
                            $first_category->for_category()->first()->name }}</span>
                    </a>
                    @endif
                    <h2><a href="{{ route('front_page_detail', ['page' => $tren->id, 'alias' => $tren->url_alias]) }}"
                            data-animation="fadeInUp" data-delay=".4s" data-duration="1000ms">{{ $tren->title }}</a>
                    </h2>
                    <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                        {{ $tren->writerName() }} -
                        {{ date('M d, Y', strtotime($tren->updated_at)) }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
{{-- col-lg-8 --}}

{{-- col-lg-4 --}}
@section('trending_right')
@isset($trending_right)
@if ($trending_right)
@foreach ($trending_right as $tren_r)
<div class="col-lg-12 col-md-6 col-sm-6">
    <div class="trending-top mb-30">
        <div class="trend-top-img">
            <img src="{{ $tren_r->getImagePath() }}" alt="">
            <div class="trend-top-cap trend-top-cap2">
                <span class="bgg">{{ $tren_r->to_category()->first()->for_category()->first()->name }}</span>
                <h2><a href="{{ route('front_page_detail', ['page' => $tren_r->id, 'alias' => $tren_r->url_alias]) }}">{{
                        $tren_r->title }}</a>
                </h2>
                <p>by {{ $tren_r->writerName() }}
                    -
                    {{ date('M d, Y', strtotime($tren_r->updated_at)) }}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endisset
</div>
@endsection
{{-- col-lg-4 --}}

{{-- ========================new_post============================ --}}

{{-- col-lg-8 left --}}
@section('new_post_left')
<div class="whats-news-wrapper">

    <!-- Heading & Nav Button -->
    <div class="row justify-content-between align-items-end mb-15">
        <div class="col-md-12">
            <div class="section-tittle mb-30">
                <h3>{{ 'Tags: '.$tag }}</h3>
            </div>
        </div>

    </div>
    <!-- Heading & Nav Button -->

    <!-- Tab content -->
    <div class="row">
        <div class="col-12">
            <!-- Nav Card -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane active" id="{{ 'nav-' . $tag }}" role="tabpanel"
                    aria-labelledby="{{ 'nav-' . $tag . '-tab' }}">
                    <div class="row">
                        <!-- Right single caption -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="whats-news-single mb-20">
                                <!-- single -->
                                @if ($papers)
                                @foreach ($papers as $paper)
                                <div class="row">
                                    <div class="whats-right-single mb-10">
                                        <div class="col-md-6">
                                            <img src="{{ $paper->getImagePath() }}" class="whates-img"
                                                style="width: 100%; height: auto;" alt="">
                                        </div>
                                        <div class="col-md-6 whats-right-cap">
                                            <h4>
                                                <a
                                                    href="{{ route('front_page_detail', ['alias' => $paper->url_alias, 'page' => $paper->id]) }}">
                                                    <h4>
                                                        {{ $paper->title }}
                                                    </h4>
                                                    {{ $paper->short_conten }}
                                                </a>
                                            </h4>
                                            <span class="colorb">{{ $paper->writerName() }}
                                            </span>
                                            <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Nav Card -->
        </div>
    </div>
    <!-- Tab content -->
</div>
@endsection

@section('new_post_banner')

@endsection
{{-- col-lg-8 --}}

@section('weekly3_news')

@endsection

@section('weekly2_news')

@endsection

{{-- =====================new_post=============================== --}}
{{-- row --}}
@section('banner_last_conten')
<div class="col-lg-10 col-md-10">
    <div class="banner-one">
        <img src={{ $DomHtml->getConfig('last_conten_image') ?:
        asset('assets/frontend/img/gallery/body_card3.png') }}
        width={{ $DomHtml->getConfig('home_image_width', 944)}}
        height={{ $DomHtml->getConfig('home_image_height', 152)}} />
    </div>
</div>
@endsection
{{-- row --}}
{{-- =====================banner_last=============================== --}}