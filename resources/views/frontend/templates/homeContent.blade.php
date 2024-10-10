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
    <div id="most-populator">
        {{-- @render(\App\ViewBlock\MostPopulator::class) --}}
    </div>
@endsection
{{-- =====================weekly2_banner=============================== --}}


{{-- =====================articles=============================== --}}
@section('articles_title')
    <div class="col-lg-12">
        <div class="section-tittle mb-30 mt-20 ml-15">
            <h3>Trending News</h3>
        </div>
    </div>
@endsection

@section('articles_conten')
    {{-- @render(App\ViewBlock\Trending::class) --}}
@endsection
{{-- =====================articles=============================== --}}


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


{{-- =====================weekly3_news=============================== --}}
@section('weekly3_conten')
    <div id="likeMost">
        {{-- @render(App\ViewBlock\LikeMost::class) --}}
    </div>
@endsection
{{-- =====================weekly3_news=============================== --}}


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

@section('js_after')
    <script>
        var mostPopulatorUrl = "{{ route('front_most_populator_html') }}";
        var likeMost = "{{ route('front_most_like_html') }}";
        var trending = "{{ route('front_trending_html') }}";

        $(document).ready(function() {
            $.ajax({
                url: mostPopulatorUrl,
                type: "GET",
                success: function(result) {
                    if (result.dataHtml) {
                        let mostPopur = $("#most-populator")
                        mostPopur.append(result.dataHtml);
                        $('.weekly2-news-active').slick({
                            dots: false,
                            infinite: true,
                            speed: 500,
                            arrows: true,
                            autoplay: true,
                            loop: true,
                            slidesToShow: 3,
                            prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
                            nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
                            slidesToScroll: 1,
                            responsive: [{
                                    breakpoint: 1200,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1,
                                        infinite: true,
                                        dots: false,
                                    }
                                },
                                {
                                    breakpoint: 992,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 700,
                                    settings: {
                                        arrows: false,
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        arrows: false,
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });
                    }
                }
            })

            $.ajax({
                url: likeMost,
                type: "GET",
                success: function(result) {
                    if (result.dataHtml) {
                        let likeMost = $("#likeMost")
                        likeMost.append(result.dataHtml);
                        $('.weekly3-news-active').slick({
                            dots: true,
                            infinite: true,
                            speed: 500,
                            arrows: false,
                            autoplay: true,
                            loop: true,
                            slidesToShow: 4,
                            prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
                            nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
                            slidesToScroll: 1,
                            responsive: [{
                                    breakpoint: 1200,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1,
                                        infinite: true,
                                        dots: true,
                                    }
                                },
                                {
                                    breakpoint: 992,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 700,
                                    settings: {
                                        arrows: false,
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        arrows: false,
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });
                    }
                }
            })

            $.ajax({
                url: trending,
                type: "GET",
                success: function(result) {
                    if (result.dataHtml) {
                        let trending = $("#most-trending")
                        trending.append(result.dataHtml);
                        $('.recent-active').slick({
                            dots: false,
                            infinite: true,
                            speed: 600,
                            arrows: false,
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            prevArrow: '<button type="button" class="slick-prev"> <span class="flaticon-arrow"></span></button>',
                            nextArrow: '<button type="button" class="slick-next"> <span class="flaticon-arrow"><span></button>',

                            initialSlide: 3,
                            loop: true,
                            responsive: [{
                                    breakpoint: 1024,
                                    settings: {
                                        slidesToShow: 3,
                                        slidesToScroll: 3,
                                        infinite: true,
                                        dots: false,
                                    }
                                },
                                {
                                    breakpoint: 992,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                    }
                                },
                                {
                                    breakpoint: 768,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        });
                    }
                }
            })
        })
    </script>
@endsection
