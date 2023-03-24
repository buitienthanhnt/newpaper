@extends('frontend.templates.pagestr')

@section('trending_left')
    <div class="single-slider">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src={{ asset('assets/frontend/img/trending/trending_top2.jpg') }} alt="">
                <div class="trend-top-cap">
                    <span class="bgr" data-animation="fadeInUp" data-delay=".2s" data-duration="1000ms">Business</span>
                    <h2><a href="latest_news.html" data-animation="fadeInUp" data-delay=".4s" data-duration="1000ms">Anna
                            Lora Stuns In White At Her Australian
                            Premiere</a></h2>
                    <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                        Alice cloe - Jun 19, 2020</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Single -->
    <div class="single-slider">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src={{ asset('assets/frontend/img/trending/trending_top02.jpg') }} alt="">
                <div class="trend-top-cap">
                    <span class="bgr" data-animation="fadeInUp" data-delay=".2s" data-duration="1000ms">Business</span>
                    <h2><a href="latest_news.html" data-animation="fadeInUp" data-delay=".4s" data-duration="1000ms">Anna
                            Lora Stuns In White At Her Australian
                            Premiere</a></h2>
                    <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                        Alice cloe - Jun 19, 2020</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Single -->
    <div class="single-slider">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src={{ asset('assets/frontend/img/trending/trending_top03.jpg') }} alt="">
                <div class="trend-top-cap">
                    <span class="bgr" data-animation="fadeInUp" data-delay=".2s" data-duration="1000ms">Business</span>
                    <h2><a href="latest_news.html" data-animation="fadeInUp" data-delay=".4s" data-duration="1000ms">Anna
                            Lora Stuns In White At Her Australian
                            Premiere</a></h2>
                    <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                        Alice cloe - Jun 19, 2020</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('trending_right')
    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src={{ asset('assets/frontend/img/trending/trending_top3.jpg') }} alt="">
                <div class="trend-top-cap trend-top-cap2">
                    <span class="bgb">FASHION</span>
                    <h2><a href="latest_news.html">Secretart for Economic Air
                            plane that looks like</a></h2>
                    <p>by Alice cloe - Jun 19, 2020</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="trending-top mb-30">
            <div class="trend-top-img">
                <img src={{ asset('assets/frontend/img/trending/trending_top4.jpg') }} alt="">
                <div class="trend-top-cap trend-top-cap2">
                    <span class="bgg">TECH </span>
                    <h2><a href="latest_news.html">Secretart for Economic Air plane that looks
                            like</a></h2>
                    <p>by Alice cloe - Jun 19, 2020</p>
                </div>
            </div>
        </div>
    </div>
@endsection
