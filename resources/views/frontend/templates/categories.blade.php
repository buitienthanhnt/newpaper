@extends('frontend/layouts/category_view')
@inject('DomHtml', 'App\Helper\HelperFunction')

@section('page_title')
    {{ $category->name }}
@endsection

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

{{-- col-lg-9 --}}
@section('weekly2_conten')
    <div id="most-populator">
        {{-- @render(\App\ViewBlock\MostPopulator::class) --}}
    </div>
@endsection
{{-- col-lg-9 --}}

{{-- =====================weekly3_news=============================== --}}
{{-- col-lg-12 --}}
@section('weekly3_conten')
    <div id="likeMost">
        {{-- @render(App\ViewBlock\LikeMost::class) --}}
    </div>
@endsection
{{-- col-lg-12 --}}
{{-- =====================weekly3_news=============================== --}}

{{-- ========================new_post============================ --}}

{{-- col-lg-8 left --}}
@section('new_post_left')
    <div class="whats-news-wrapper">
        <!-- Heading & Nav Button -->
        <div class="row justify-content-between align-items-end mb-15">
            <div class="col-md-12">
                <div class="section-tittle mb-30">
                    <h3>{{ $category->name }}</h3>
                </div>
            </div>

        </div>
        <!-- Heading & Nav Button -->

        <!-- Tab content -->
        <div class="row">
            <div class="col-12">
                <!-- Nav Card -->
                <div class="tab-content" id="nav-tabContent">
                    <div class="row">
                        <!-- Left Details Caption -->
                        @if ($papers)
                            @foreach ($papers->take(2) as $paper_first)
                                <div class="col-xl-6">
                                    <div class="whats-news-single mb-20">
                                        <div class="whates-img">
                                            <img src="{{ $paper_first->getImagePath() }}"
                                                style="max-height: 210px; object-fit: cover" alt="">
                                        </div>
                                        <div class="whates-caption">
                                            <h4><a class="text-info"
                                                    href="{{ route('front_paper_detail', ['alias' => $paper_first->url_alias, 'paper_id' => $paper_first->id]) }}">{{ $paper_first->title }}</a>
                                            </h4>
                                            <h6>{{ $paper_first->short_conten }}</h6>
                                            {!! view('frontend.templates.elements.dateTime', ['paper' => $paper_first])->render() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <!-- Right single caption -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="whats-news-single mb-20" id="whats-right-single">
                                <!-- single -->
                                @if ($papers)
                                    @foreach ($papers->take(-2) as $paper)
                                        <div class="row">
                                            <div class="whats-right-single mb-10">
                                                <div class="col-md-6">
                                                    <img src="{{ $paper->getImagePath() }}" class="whates-img"
                                                        style="width: 100%; height: auto; min-height: 160px" alt="">
                                                </div>
                                                <div class="col-md-6 whats-right-cap" style="padding-left: 15px">
                                                    <h4>
                                                        <a
                                                            href="{{ route('front_paper_detail', ['alias' => $paper->url_alias, 'paper_id' => $paper->id]) }}">
                                                            <h4 class="text-info">{{ $paper->title }}</h4>
                                                        </a>
                                                    </h4>
                                                    <h6>{{ $paper->short_conten }}</h6>
                                                    <span class="colorb" style="color: #ff2143">
                                                        {{ $paper->writerName() }}
                                                    </span>
                                                    <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}
                                                        <a href="" class="text text-info" style="float: right;"><i
                                                                class="fa fa-eye"></i> {{ $paper->viewCount() }}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <center>
                            {{-- https://www.w3schools.com/icons/fontawesome5_icons_spinners.asp --}}
                            <button id="load_more" data-page="1" class="btn btn-info" style="border-radius: 5px"
                                onclick="load_more()">
                                Load more
                            </button>
                        </center>

                    </div>
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
        var token = "{{ csrf_token() }}";
        var url = "{{ route('front_load_more') }}";
        var mostPopulatorUrl = "{{ route('front_most_populator_html') }}";
        var likeMost = "{{ route('front_most_like_html') }}";
        var type = "{{ $category->url_alias }}"

        function load_more() {
            var button = $("#load_more");
            let page = button.attr("data-page");
            button.html('<i class="fas fa-spinner fa-spin"></i>').attr('disabled', true);
            if (page) {
                $.ajax({
                    url: url + "?page=" + page + "&type=" + type,
                    type: "GET",
                    success: function(result) {
                        result = JSON.parse(result);
                        let button = $("#load_more");
                        if (result.data) {
                            let data = result.data;
                            let conten = $("#whats-right-single");
                            // conten.after(data);
                            conten.append(data);
                            button.html('Load more').attr('disabled', false).attr("data-page", Number(button
                                .attr("data-page")) + 1);
                        } else {
                            let button = $("#load_more");
                            button.html('end conten').attr('disabled', true)
                        }
                    },
                    error: function(error) {
                        let button = $("#load_more");
                        button.html('load more').attr('disabled', false)
                    }
                });
            }
        }

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
        })
    </script>
@endsection
