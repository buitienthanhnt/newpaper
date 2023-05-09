@extends('frontend.layouts.home_default')

{{-- ========================trending============================ --}}

{{-- col-lg-8 --}}
@section('trending_left')
    <div class="slider-active">
        @if ($trending_left)
            @foreach ($trending_left as $tren)
                <div class="single-slider">
                    <div class="trending-top mb-30">
                        <div class="trend-top-img">
                            <img src="{{ $tren->image_path }}" alt="">
                            <div class="trend-top-cap">
                                <a href="">
                                    <span class="bgr" data-animation="fadeInUp" data-delay=".2s"
                                        data-duration="1000ms">{{ $tren->to_category()->first()->for_category()->first()->name }}</span>
                                </a>
                                <h2><a href="latest_news.html" data-animation="fadeInUp" data-delay=".4s"
                                        data-duration="1000ms">{{ $tren->short_conten }}</a></h2>
                                <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                                    {{ $tren->to_writer()->getResults()->name }} -
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
                        <img src="{{ $tren_r->image_path }}" alt="">
                        <div class="trend-top-cap trend-top-cap2">
                            <span class="bgg">{{ $tren_r->to_category()->first()->for_category()->first()->name }}</span>
                            <h2><a href="latest_news.html">{{ $tren_r->short_conten }}</a></h2>
                            <p>by {{ $tren_r->to_writer()->getResults()->name }} -
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
                                    <!-- Left Details Caption -->
                                    <div class="col-xl-6">
                                        <div class="whats-news-single mb-40">
                                            <div class="whates-img">
                                                <img src={{ asset('assets/frontend/img/gallery/whats_right_img2.png') }}
                                                    alt="">
                                            </div>
                                            <div class="whates-caption">
                                                <h4><a href="#">{{ $center_conten->name }}</a></h4>
                                                <span>by Alice cloe - Jun 19, 2020</span>
                                                <p>Struggling to sell one multi-million dollar home currently on
                                                    the market won’t stop actress and singer Jennifer Lopez.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Right single caption -->
                                    <div class="col-xl-6 col-lg-12">
                                        <div class="row">
                                            <!-- single -->
                                            @if ($papers = $center_conten->get_product())
                                                @foreach ($papers as $paper)
                                                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-10">
                                                        <div class="whats-right-single mb-20">
                                                            <div class="whats-right-img">
                                                                <img src="{{ $paper->image_path }}" style="width: 124px; height: 104px;"
                                                                    alt="">
                                                            </div>
                                                            <div class="whats-right-cap">
                                                                <span class="colorb">{{ $paper->to_writer()->getResults()->name }}</span>
                                                                <h4><a href="latest_news.html">{{ $paper->short_conten }}</a></h4>
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
        <div class="most-recent mb-40">
            <div class="most-recent-img">
                <img src={{ asset('assets/frontend/img/gallery/most_recent.png') }} alt="">
                <div class="most-recent-cap">
                    <span class="bgbeg">Vogue</span>
                    <h4><a href="latest_news.html">What to Wear: 9+ Cute Work <br>
                            Outfits to Wear This.</a></h4>
                    <p>Jhon | 2 hours ago</p>
                </div>
            </div>
        </div>
        <!-- Single -->
        <div class="most-recent-single">
            <div class="most-recent-images">
                <img src={{ asset('assets/frontend/img/gallery/most_recent1.png') }} alt="">
            </div>
            <div class="most-recent-capt">
                <h4><a href="latest_news.html">Scarlett’s disappointment at latest accolade</a></h4>
                <p>Jhon | 2 hours ago</p>
            </div>
        </div>
        <!-- Single -->
        <div class="most-recent-single">
            <div class="most-recent-images">
                <img src={{ asset('assets/frontend/img/gallery/most_recent2.png') }} alt="">
            </div>
            <div class="most-recent-capt">
                <h4><a href="latest_news.html">Most Beautiful Things to Do in Sidney with Your BF</a>
                </h4>
                <p>Jhon | 3 hours ago</p>
            </div>
        </div>
    </div>
@endsection
{{-- col-lg-4 --}}

{{-- =====================new_post=============================== --}}


{{-- =====================weekly2_banner=============================== --}}

{{-- col-lg-3 --}}
@section('weekly2_banner')
    <div class="home-banner2 d-none d-lg-block">
        <img src={{ asset('assets/frontend/img/gallery/body_card2.png') }} alt="">
    </div>
@endsection
{{-- col-lg-3 --}}

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
                    <div class="weekly2-single">
                        <div class="weekly2-img">
                            <img src={{ asset('assets/frontend/img/gallery/weeklyNews1.png') }} alt="">
                        </div>
                        <div class="weekly2-caption">
                            <h4><a href="#">Scarlett’s disappointment at latest accolade</a>
                            </h4>
                            <p>Jhon | 2 hours ago</p>
                        </div>
                    </div>
                    <!-- Single -->
                    <div class="weekly2-single">
                        <div class="weekly2-img">
                            <img src={{ asset('assets/frontend/img/gallery/weeklyNews2.png') }} alt="">
                        </div>
                        <div class="weekly2-caption">
                            <h4><a href="#">Scarlett’s disappointment at latest accolade</a>
                            </h4>
                            <p>Jhon | 2 hours ago</p>
                        </div>
                    </div>
                    <!-- Single -->
                    <div class="weekly2-single">
                        <div class="weekly2-img">
                            <img src={{ asset('assets/frontend/img/gallery/weeklyNews3.png') }} alt="">
                        </div>
                        <div class="weekly2-caption">
                            <h4><a href="#">Scarlett’s disappointment at latest accolade</a>
                            </h4>
                            <p>Jhon | 2 hours ago</p>
                        </div>
                    </div>
                    <!-- Single -->
                    <div class="weekly2-single">
                        <div class="weekly2-img">
                            <img src={{ asset('assets/frontend/img/gallery/weeklyNews2.png') }} alt="">
                        </div>
                        <div class="weekly2-caption">
                            <h4><a href="#">Scarlett’s disappointment at latest accolade</a>
                            </h4>
                            <p>Jhon | 2 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- col-lg-9 --}}

{{-- =====================weekly2_banner=============================== --}}


{{-- =====================articles=============================== --}}
{{-- row --}}
@section('articles_title')
    <div class="col-lg-12">
        <div class="section-tittle mb-30">
            <h3>Trending News</h3>
        </div>
    </div>
@endsection
{{-- row --}}

{{-- row --}}
@section('articles_conten')
    <div class="col-12">
        <div class="recent-active dot-style d-flex dot-style">
            <!-- Single -->
            <div class="single-recent">
                <div class="what-img">
                    <img src={{ asset('assets/frontend/img/gallery/tranding1.png') }} alt="">
                </div>
                <div class="what-cap">
                    <h4><a href="#">
                            <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                                    ations</a></h4>
                        </a></h4>
                    <P>Jun 19, 2020</P>
                    <a class="popup-video btn-icon" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                            class="flaticon-play-button"></span></a>

                </div>
            </div>
            <!-- Single -->
            <div class="single-recent">
                <div class="what-img">
                    <img src={{ asset('assets/frontend/img/gallery/tranding2.png') }} alt="">
                </div>
                <div class="what-cap">
                    <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin ations</a>
                    </h4>
                    <P>Jun 19, 2020</P>
                    <a class="popup-video" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                            class="flaticon-play-button"></span></a>
                </div>
            </div>
            <!-- Single -->
            <div class="single-recent">
                <div class="what-img">
                    <img src={{ asset('assets/frontend/img/gallery/tranding1.png') }} alt="">
                </div>
                <div class="what-cap">
                    <h4><a href="latest_news.html">
                            <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                                    ations</a></h4>
                        </a></h4>
                    <P>Jun 19, 2020</P>
                    <a class="popup-video" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                            class="flaticon-play-button"></span></a>
                </div>
            </div>
            <!-- Single -->
            <div class="single-recent">
                <div class="what-img">
                    <img src={{ asset('assets/frontend/img/gallery/tranding2.png') }} alt="">
                </div>
                <div class="what-cap">
                    <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin ations</a>
                    </h4>
                    <P>Jun 19, 2020</P>
                    <a class="popup-video" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                            class="flaticon-play-button"></span></a>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- row --}}

{{-- =====================articles=============================== --}}


{{-- =====================video_area=============================== --}}
{{-- container --}}
@section('video_area_conten')
    <div class="row">

        <div class="col-12">
            <div class="video-items-active">

                <div class="video-items text-center">
                    <video controls>
                        <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-items text-center">
                    <video controls>
                        <source src={{ asset('assets/frontend/video/news1.mp4') }} type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-items text-center">
                    <video controls>
                        <source src={{ asset('assets/frontend/video/news3.mp4') }} type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-items text-center">
                    <video controls>
                        <source src={{ asset('assets/frontend/video/news1.mp4') }} type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-items text-center">
                    <video controls>
                        <source src={{ asset('assets/frontend/video/news3.mp4') }} type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

            </div>
        </div>

    </div>

    <div class="video-info">
        <div class="row">
            <div class="col-12">
                <div class="testmonial-nav text-center">
                    <div class="single-video">
                        <video controls>
                            <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-intro">
                            <h4>Old Spondon News - 2020 </h4>
                        </div>
                    </div>
                    <div class="single-video">
                        <video controls>
                            <source src={{ asset('assets/frontend/video/news1.mp4') }} type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-intro">
                            <h4>Banglades News Video </h4>
                        </div>
                    </div>
                    <div class="single-video">
                        <video controls>
                            <source src={{ asset('assets/frontend/video/news3.mp4') }} type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-intro">
                            <h4>Latest Video - 2020 </h4>
                        </div>
                    </div>
                    <div class="single-video">
                        <video controls>
                            <source src={{ asset('assets/frontend/video/news1.mp4') }} type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-intro">
                            <h4>Spondon News -2019 </h4>
                        </div>
                    </div>
                    <div class="single-video">
                        <video controls>
                            <source src={{ asset('assets/frontend/video/news3.mp4') }} type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-intro">
                            <h4>Latest Video - 2020</h4>
                        </div>
                    </div>
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
    <div class="weekly3-news-active dot-style d-flex">

        <div class="weekly3-single">
            <div class="weekly3-img">
                <img src={{ asset('assets/frontend/img/gallery/weekly2News1.png') }} alt="">
            </div>
            <div class="weekly3-caption">
                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                        ations</a></h4>
                <p>19 Jan 2020</p>
            </div>
        </div>

        <div class="weekly3-single">
            <div class="weekly3-img">
                <img src={{ asset('assets/frontend/img/gallery/weekly2News2.png') }} alt="">
            </div>
            <div class="weekly3-caption">
                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                        ations</a></h4>
                <p>19 Jan 2020</p>
            </div>
        </div>

        <div class="weekly3-single">
            <div class="weekly3-img">
                <img src={{ asset('assets/frontend/img/gallery/weekly2News3.png') }} alt="">
            </div>
            <div class="weekly3-caption">
                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                        ations</a></h4>
                <p>19 Jan 2020</p>
            </div>
        </div>

        <div class="weekly3-single">
            <div class="weekly3-img">
                <img src={{ asset('assets/frontend/img/gallery/weekly2News4.png') }} alt="">
            </div>
            <div class="weekly3-caption">
                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                        ations</a></h4>
                <p>19 Jan 2020</p>
            </div>
        </div>

        <div class="weekly3-single">
            <div class="weekly3-img">
                <img src={{ asset('assets/frontend/img/gallery/weekly2News2.png') }} alt="">
            </div>
            <div class="weekly3-caption">
                <h4><a href="latest_news.html">What to Expect From the 2020 Oscar Nomin
                        ations</a></h4>
                <p>19 Jan 2020</p>
            </div>
        </div>

    </div>
@endsection
{{-- col-lg-12 --}}
{{-- =====================weekly3_news=============================== --}}


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
