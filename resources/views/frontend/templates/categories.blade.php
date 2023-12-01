@extends('frontend/layouts/category_view')

@section('page_title')
    {{ $category->name }}
@endsection

{{-- col-lg-8 --}}
@section('trending_left')
    <div class="slider-active">
        @if ($trending_left)
            @foreach ($trending_left as $tren)
                <div class="single-slider">
                    <div class="trending-top mb-30">
                        <div class="trend-top-img">
                            <img src="{{ $tren->image_path ?: asset('assets/pub_image/defaul.PNG') }}" alt="">
                            <div class="trend-top-cap">
                                @if ($first_category = $tren->to_category()->first())
                                    <a href="">
                                        <span class="bgr" data-animation="fadeInUp" data-delay=".2s"
                                            data-duration="1000ms">{{ $first_category->for_category()->first()->name }}</span>
                                    </a>
                                @endif
                                <h2><a href="{{ route('front_page_detail', ['page' => $tren->id, 'alias' => $tren->url_alias]) }}"
                                        data-animation="fadeInUp" data-delay=".4s"
                                        data-duration="1000ms">{{ $tren->title }}</a></h2>
                                <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                                    {{ $tren->to_writer()->getResults() ? $tren->to_writer()->getResults()->name : '' }} -
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
    @if ($trending_right)
        @foreach ($trending_right as $tren_r)
            <div class="col-lg-12 col-md-6 col-sm-6">
                <div class="trending-top mb-30">
                    <div class="trend-top-img">
                        <img src="{{ $tren_r->image_path ?: asset('assets/pub_image/defaul.PNG') }}" alt="">
                        <div class="trend-top-cap trend-top-cap2">
                            <span class="bgg">{{ $tren_r->to_category()->first() ? $tren_r->to_category()->first()->for_category()->first()->name: '' }}</span>
                            <h2><a
                                    href="{{ route('front_page_detail', ['page' => $tren_r->id, 'alias' => $tren_r->url_alias]) }}">{{ $tren_r->title }}</a>
                            </h2>
                            <p>by {{ $tren_r->to_writer()->getResults() ? $tren_r->to_writer()->getResults()->name : '' }}
                                -
                                {{ date('M d, Y', strtotime($tren_r->updated_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    </div>
@endsection
{{-- col-lg-4 --}}

{{-- col-lg-9 --}}
@section('weekly2_conten')
    <div class="slider-wrapper">
        <!-- section Tittle -->
        <div class="row">
            <div class="col-lg-12">
                <div class="small-tittle mb-30">
                    <h4>Most Popular</h4>
                </div>
            </div>
        </div>
        <!-- Slider -->
        <div class="row">
            <div class="col-lg-12">
                <div class="weekly2-news-active d-flex">
                    <!-- Single -->
                    @if ($most_popular)
                        @foreach ($most_popular as $popular_item)
                            <div class="weekly2-single">
                                <div class="weekly2-img">
                                    <img src="{{ $popular_item->image_path }}" alt="">
                                </div>
                                <div class="weekly2-caption">
                                    <h4><a
                                            href="{{ route('front_page_detail', ['page' => $popular_item->id, 'alias' => $popular_item->url_alias]) }}">{{ $popular_item->title }}</a>
                                    </h4>
                                    <p>{{ $popular_item->to_writer()->getResults() ? $popular_item->to_writer()->getResults()->name : '' }}
                                        | 2 hours ago</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- col-lg-9 --}}

{{-- =====================weekly3_news=============================== --}}
{{-- col-lg-12 --}}
@section('weekly3_conten')
    <div class="weekly3-news-active dot-style d-flex">
        @if ($weekly3_contens)
            @foreach ($weekly3_contens as $weekly3_conten)
                <div class="weekly3-single">
                    <div class="weekly3-img">
                        <img src="{{ $weekly3_conten->image_path }}" alt="">
                    </div>
                    <div class="weekly3-caption">
                        <h4><a href="latest_news.html">{{ $weekly3_conten->title }}</a></h4>
                        <p>{{ date('M d, Y', strtotime($weekly3_conten->updated_at)) }}</p>
                    </div>
                </div>
            @endforeach
        @endif
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
                    @if ($list_center)
                        @foreach ($list_center as $center_conten)
                            <div class="tab-pane active" id="{{ 'nav-' . $center_conten->id }}" role="tabpanel"
                                aria-labelledby="{{ 'nav-' . $center_conten->id . '-tab' }}">
                                <div class="row">
                                    <!-- Left Details Caption -->
                                    @if ($top_paper)
                                        @foreach ($top_paper as $paper_first)
                                            <div class="col-xl-6">
                                                <div class="whats-news-single mb-20">
                                                    <div class="whates-img">
                                                        <img src="{{ $paper_first->image_path }}" alt="">
                                                    </div>
                                                    <div class="whates-caption">
                                                        <h4><a
                                                                href="{{ route('front_page_detail', ['alias' => $paper_first->url_alias, 'page' => $paper_first->id]) }}">{{ $paper_first->title }}</a>
                                                        </h4>
                                                        <h6>{{ $paper_first->short_conten }}</h6>
                                                        <p>by
                                                            <a href="" class="text text-info">
                                                                {{ $paper_first->to_writer()->getResults() ? $paper_first->to_writer()->getResults()->name : '' }}
                                                            </a>
                                                            -
                                                            {{ date('M d, Y', strtotime($paper_first->updated_at)) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <!-- Right single caption -->
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="whats-news-single mb-20" id="whats-right-single">
                                            <!-- single -->
                                            @if ($top_paper && $papers)
                                                @foreach ($papers as $paper)
                                                    <div class="row">
                                                        <div class="whats-right-single mb-10">
                                                            <div class="col-md-6">
                                                                <img src="{{ $paper->image_path }}" class="whates-img"
                                                                    style="width: 100%; height: auto;" alt="">
                                                            </div>
                                                            <div class="col-md-6 whats-right-cap" style="padding-left: 15px">
                                                                <h4>
                                                                    <a
                                                                        href="{{ route('front_page_detail', ['alias' => $paper->url_alias, 'page' => $paper->id]) }}">
                                                                        <h4>{{ $paper->title }}</h4>
                                                                    </a>
                                                                </h4>
                                                                <h6>{{ $paper->short_conten }}</h6>
                                                                <span class="colorb">
                                                                    {{ $paper->to_writer()->getResults() ? $paper->to_writer()->getResults()->name : '' }}
                                                                </span>
                                                                <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div>
                        <center>
                            <button id="load_more" data-page="1" class="btn btn-info" onclick="load_more()">show
                                more</button>
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
        <img src={{ asset('assets/frontend/img/gallery/body_card1.png') }} alt="">
    </div>
@endsection
{{-- col-lg-8 --}}

{{-- col-lg-4 right --}}
@section('flow_socail')
    <div class="single-follow mb-45">
        <div class="single-box">
            <div class="follow-us d-flex align-items-center">
                <div class="follow-social">
                    <a href="#"><img src={{ asset('assets/frontend/img/news/icon-fb.png') }} alt=""></a>
                </div>
                <div class="follow-count">
                    <span>8,045</span>
                    <p>Fans</p>
                </div>
            </div>
            <div class="follow-us d-flex align-items-center">
                <div class="follow-social">
                    <a href="#"><img src={{ asset('assets/frontend/img/news/icon-tw.png') }} alt=""></a>
                </div>
                <div class="follow-count">
                    <span>8,045</span>
                    <p>Fans</p>
                </div>
            </div>
            <div class="follow-us d-flex align-items-center">
                <div class="follow-social">
                    <a href="#"><img src={{ asset('assets/frontend/img/news/icon-ins.png') }} alt=""></a>
                </div>
                <div class="follow-count">
                    <span>8,045</span>
                    <p>Fans</p>
                </div>
            </div>
            <div class="follow-us d-flex align-items-center">
                <div class="follow-social">
                    <a href="#"><img src={{ asset('assets/frontend/img/news/icon-yo.png') }} alt=""></a>
                </div>
                <div class="follow-count">
                    <span>8,045</span>
                    <p>Fans</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('most_recent')
    <div class="most-recent-area">
        <!-- Section Tittle -->
        <div class="small-tittle mb-20">
            <h4>Most Recent</h4>
        </div>
        <!-- Details -->
        @if ($most_recent && ($first_recent = $most_recent->first()))
            <div class="most-recent mb-40">
                <div class="most-recent-img">
                    <img src="{{ $first_recent->image_path }}" alt="">
                    <div class="most-recent-cap">
                        <span class="bgbeg">Vogue</span>
                        <h4><a href="latest_news.html">What to Wear: 9+ Cute Work <br>
                                Outfits to Wear This.</a></h4>
                        <p>{{ $first_recent->to_writer()->getResults() ? $first_recent->to_writer()->getResults()->name : '' }}
                            | 2 hours ago</p>
                    </div>
                </div>
            </div>
            @if ($af_recents = $most_recent->diff([$most_recent->first()]))
                @foreach ($af_recents as $af_recent)
                    <div class="most-recent-single">
                        <div class="most-recent-images">
                            <img src="{{ $af_recent->image_path }}" style="width: 85px; height: 79px;" alt="">
                        </div>
                        <div class="most-recent-capt">
                            <h4><a href="latest_news.html">{{ $af_recent->title }}</a></h4>
                            <p>{{ $af_recent->to_writer()->getResults() ? $af_recent->to_writer()->getResults()->name : '' }}
                                | 2 hours ago</p>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endsection
{{-- col-lg-4 --}}

{{-- =====================new_post=============================== --}}

{{-- =====================banner_last=============================== --}}
{{-- row --}}
@section('banner_last_conten')
    <div class="col-lg-10 col-md-10">
        <div class="banner-one">
            <img src={{ asset('assets/frontend/img/gallery/body_card3.png') }} alt="">
        </div>
    </div>
@endsection
{{-- row --}}
{{-- =====================banner_last=============================== --}}


@section('js_after')
    <script>
        var token = "{{ csrf_token() }}";
        var url = "{{ route('load_more') }}";
        var type = "{{ $category->url_alias }}"

        function load_more() {
            var button = $("#load_more");
            let page = button.attr("data-page");
            if (page) {
                $.ajax({
                    url: url + "?page=" + page + "&type=" + type,
                    type: "GET",
                    success: function(result) {
                        result = JSON.parse(result);
                        if (result.data) {
                            let data = result.data;
                            let conten = $("#whats-right-single");
                            // conten.after(data);
                            conten.append(data);
                            let button = $("#load_more");
                            button.attr("data-page", Number(button.attr("data-page")) + 1);
                        }
                    },
                    error: function(error) {

                    }
                });
            }
        }
    </script>
@endsection
