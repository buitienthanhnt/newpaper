@extends('frontend.layouts.home_default')
@inject('DomHtml', 'App\Helper\HelperFunction')
{{-- ========================trending============================ --}}

{!! view('elements.message.index')->render(); !!}
@section('trending_left')
    @render(App\ViewBlock\TrendingLeft::class)
@endsection

@section('trending_right')
    @render(App\ViewBlock\TrendingRight::class)
@endsection
{{-- ========================trending============================ --}}

{{-- ========================new_post============================ --}}
@section('new_post_left')
    @render(App\ViewBlock\CenterCategory::class)
@endsection

@section('new_post_banner')
    <div class="banner-one mt-20 mb-30">
        <img src={{ $DomHtml->getConfig('post_banner_image') ?: asset('assets/frontend/img/gallery/body_card1.png') }}
            width={{ $DomHtml->getConfig('home_image_width', 750) }}
            height={{ $DomHtml->getConfig('home_image_height', 111) }} />
    </div>
@endsection

@section('flow_socail')
    {!! view('frontend.templates.share.social')->render() !!}
@endsection

@section('most_recent')
    @render(App\ViewBlock\MostRecent::class)
@endsection
{{-- =====================new_post=============================== --}}

{{-- =====================weekly2_banner=============================== --}}
@section('weekly2_banner')
    <div class="home-banner2 d-none d-lg-block">
        <img src={{ $DomHtml->getConfig('weekly_image') ?: asset('assets/frontend/img/gallery/body_card2.png') }}
            width={{ $DomHtml->getConfig('home_image_width', 264) }}
            height={{ $DomHtml->getConfig('home_image_height', 354) }} />
    </div>
@endsection

@section('weekly2_conten')
    {!! view('frontend.templates.homePage.mostPopulator') !!}
@endsection
{{-- =====================weekly2_banner=============================== --}}

{{-- =====================articles=============================== --}}
@section('articles_conten')
    {!! view('frontend.templates.homePage.mostTrending') !!}
@endsection
{{-- =====================articles=============================== --}}

{{-- =====================weekly3_news=============================== --}}
@section('weekly3_conten')
    {!! view('frontend.templates.homePage.mostLike') !!}
@endsection
{{-- =====================weekly3_news=============================== --}}

{{-- =====================video_area=============================== --}}
@section('video_area_conten')
    <div class="row">
        <div class="col-12">
            <div class="video-items-active">
                @if ($video_contens)
                    @foreach ($video_contens as $video)
                        <div class="video-items text-center">
                            <iframe class="w-100" style="height: 520px" src="{{ $video['url'] }}"></iframe>
                            {{-- <video controls>
                                <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                                Your browser does not support the video tag.
                            </video> --}}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="video-info">
        <div class="row">
            <div class="col-12">
                <div class="testmonial-nav text-center">
                    @if ($video_contens)
                        @foreach ($video_contens as $video)
                            <div class="single-video" style="padding-top: 10px;">
                                <iframe class="w-100" src="{{ $video['url'] }}"></iframe>
                                {{-- <video controls>
                                    <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> --}}
                                <div class="video-intro">
                                    <h4>{{ $video['title'] }}</h4>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- =====================video_area=============================== --}}

{{-- =====================banner_last=============================== --}}
@section('banner_last_conten')
    <div class="col-lg-10 col-md-10">
        <div class="banner-one">
            <img src={{ $DomHtml->getConfig('last_conten_image') ?: asset('assets/frontend/img/gallery/body_card3.png') }}
                width={{ $DomHtml->getConfig('home_image_width', 944) }}
                height={{ $DomHtml->getConfig('home_image_height', 152) }} />
        </div>
    </div>
@endsection
{{-- =====================banner_last=============================== --}}
