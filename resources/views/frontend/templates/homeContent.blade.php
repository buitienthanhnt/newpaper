@extends('frontend.layouts.home_default')
@inject('DomHtml', 'App\Helper\HelperFunction')
{{-- ========================trending============================ --}}

{!! view('elements.message.index')->render(); !!}
{{-- col-lg-8 --}}
@section('trending_left')
    @render(App\ViewBlock\TrendingLeft::class)
@endsection
{{-- col-lg-8 --}}

{{-- col-lg-4 --}}
@section('trending_right')
    @render(App\ViewBlock\TrendingRight::class)
@endsection
{{-- col-lg-4 --}}

{{-- ========================trending============================ --}}


{{-- ========================new_post============================ --}}

{{-- col-lg-8 left --}}
@section('new_post_left')
    <div class="whats-news-wrapper">

        <!-- Heading & Nav Button -->
        <div class="row justify-content-between align-items-end mb-15">
            <div class="col-xl-4">
                <div class="section-tittle mb-30">
                    <h3>Whats New</h3>
                </div>
            </div>
            <div class="col-xl-8 col-md-9">
                <div class="properties__button">
                    <!--Nav Button  -->
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            @if ($list_center)
                                <?php $i = 0; ?>
                                @foreach ($list_center as $center_item)
                                    <a class="nav-item nav-link {{ $i == 0 ? 'active' : '' }}"
                                        id="{{ 'nav-' . $center_item->id . '-tab' }}" data-toggle="tab"
                                        href="{{ '#nav-' . $center_item->id }}" role="tab"
                                        aria-controls="{{ 'nav-' . $center_item->id }}"
                                        aria-selected="{{ $i == 0 ? 'true' : 'false' }}">{{ $center_item->name }}</a>
                                    <?php $i += 1; ?>
                                @endforeach
                            @endif
                        </div>
                    </nav>
                    <!--End Nav Button  -->
                </div>
            </div>
        </div>
        <!-- Heading & Nav Button -->

        <!-- Tab content -->
        <div class="row">
            <div class="col-12">
                <!-- Nav Card -->
                <div class="tab-content" id="nav-tabContent">
                    @if ($list_center)
                        <?php $center_one = 0; ?>
                        @foreach ($list_center as $center_conten)
                            <div class="tab-pane fade{{ !$center_one ? ' show active' : '' }}"
                                id="{{ 'nav-' . $center_conten->id }}" role="tabpanel"
                                aria-labelledby="{{ 'nav-' . $center_conten->id . '-tab' }}">
                                <div class="row">
                                    @if ($papers = $center_conten->get_papers())
                                        <!-- Left Details Caption -->
                                        @if ($paper_first = $papers->first())
                                            <div class="col-xl-6">
                                                <div class="whats-news-single mb-40">
                                                    <div class="whates-img">
                                                        <img src="{{ $paper_first->getImagePath() }}" alt="">
                                                    </div>
                                                    <div class="whates-caption">
                                                        <h4><a href="#">{{ $paper_first->title }}</a></h4>
                                                        <span>by
                                                            {{ $paper_first->writerName() }}
                                                            -
                                                            {{ date('M d, Y', strtotime($paper_first->updated_at)) }}
                                                            <a href="" class="text text-info" style="float: right;">
                                                                <i class="fa fa-eye"></i>
                                                                {{ $paper_first->viewCount() }}
                                                            </a>
                                                        </span>
                                                        <p>{{ $paper_first->short_conten }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Right single caption -->
                                        <div class="col-xl-6 col-lg-12">
                                            <div class="row">
                                                <!-- single -->
                                                @if ($papers->first() && ($papers = $papers->diff([$papers->first()])))
                                                    @foreach ($papers as $paper)
                                                        <div class="col-xl-12 col-lg-6 col-md-6 col-sm-10">
                                                            <div class="whats-right-single mb-20">
                                                                <div class="whats-right-img">
                                                                    <img src="{{ $paper->getImagePath() }}"
                                                                        style="width: 124px; height: 104px;" alt="">
                                                                </div>
                                                                <div class="whats-right-cap">
                                                                    <h4><a title="{{ $paper->short_conten }}"
                                                                            href="{{ route('front_paper_detail', ['paper_id' => $paper->id, 'alias' => $paper->url_alias]) }}">{{ $DomHtml->cut_str($paper->short_conten, 76, '...') }}</a>
                                                                    </h4>
                                                                    <p>
                                                                        {{ date('M d, Y', strtotime($paper->updated_at)) }}
                                                                        <a href="" class="text text-info"
                                                                            style="float: right;">
                                                                            {{-- <i class="fa fa-eye"></i>  --}}
                                                                            {{ $paper->viewCount() }}
                                                                        </a>
                                                                    </p>
                                                                    <span class="colorb mb-10">{{ $paper->writerName() }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <?php $center_one += 1; ?>
                        @endforeach
                    @endif
                </div>
                <!-- End Nav Card -->
            </div>
        </div>
        <!-- Tab content -->
    </div>
@endsection

@section('new_post_banner')
    <div class="banner-one mt-20 mb-30">
        <img src={{ $DomHtml->getConfig('post_banner_image') ?: asset('assets/frontend/img/gallery/body_card1.png') }}
            width={{ $DomHtml->getConfig('home_image_width', 750) }}
            height={{ $DomHtml->getConfig('home_image_height', 111) }} />
    </div>
@endsection
{{-- col-lg-8 --}}

{{-- col-lg-4 right --}}
@section('flow_socail')
    {!! view('frontend.templates.share.social')->render() !!}
@endsection

@section('most_recent')
    @render(App\ViewBlock\MostRecent::class)
@endsection
{{-- col-lg-4 --}}

{{-- =====================new_post=============================== --}}


{{-- =====================weekly2_banner=============================== --}}

{{-- col-lg-3 --}}
@section('weekly2_banner')
    <div class="home-banner2 d-none d-lg-block">
        <img src={{ $DomHtml->getConfig('weekly_image') ?: asset('assets/frontend/img/gallery/body_card2.png') }}
            width={{ $DomHtml->getConfig('home_image_width', 264) }}
            height={{ $DomHtml->getConfig('home_image_height', 354) }} />
    </div>
@endsection
{{-- col-lg-3 --}}

{{-- col-lg-9 --}}
@section('weekly2_conten')
    <div id="most-populator">
        {{-- @render(\App\ViewBlock\MostPopulator::class) --}}
    </div>
@endsection
{{-- col-lg-9 --}}

{{-- =====================weekly2_banner=============================== --}}


{{-- =====================articles=============================== --}}
{{-- row --}}
@section('articles_title')
    <div class="col-lg-12">
        <div class="section-tittle mb-30 mt-20 ml-15">
            <h3>Trending News</h3>
        </div>
    </div>
@endsection
{{-- row --}}

{{-- row --}}
@section('articles_conten')
    {{-- @render(App\ViewBlock\Trending::class) --}}
@endsection
{{-- row --}}

{{-- =====================articles=============================== --}}


{{-- =====================video_area=============================== --}}
{{-- container --}}
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
{{-- container --}}
{{-- =====================video_area=============================== --}}


{{-- =====================weekly3_news=============================== --}}
{{-- col-lg-12 --}}
@section('weekly3_conten')
    <div id="likeMost">
        {{-- @render(App\ViewBlock\LikeMost::class) --}}
    </div>
@endsection
{{-- col-lg-12 --}}
{{-- =====================weekly3_news=============================== --}}


{{-- =====================banner_last=============================== --}}
{{-- row --}}
@section('banner_last_conten')
    <div class="col-lg-10 col-md-10">
        <div class="banner-one">
            <img src={{ $DomHtml->getConfig('last_conten_image') ?: asset('assets/frontend/img/gallery/body_card3.png') }}
                width={{ $DomHtml->getConfig('home_image_width', 944) }}
                height={{ $DomHtml->getConfig('home_image_height', 152) }} />
        </div>
    </div>
@endsection
{{-- row --}}
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
