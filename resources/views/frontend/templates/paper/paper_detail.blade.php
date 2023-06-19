@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection


@section('title')
    detail page
@endsection

@section('css_after')
    <style>
        .btn {
            background: rgba(0, 123, 255, .5);
            text-transform: uppercase;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
            padding: 10px 10px;
            border-radius: 0px;
            letter-spacing: 1px;
            line-height: 0;
            -moz-user-select: none;
            cursor: pointer;
            transition: color 0.4s linear;
            position: relative;
            z-index: 1;
            border: 0;
            overflow: hidden;
            margin: 0;
            border-radius: 10px
        }

        img {
            vertical-align: middle;
            border-style: none;
            max-width: 100%;
            height: auto;
        }

        a,
        button {
            color: #007bff;
            outline: medium none
        }
    </style>
@endsection


@section('main_conten')
    <!-- About US Start -->
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Trending Tittle -->
                    <div class="about-right mb-90">
                        <div class="about-img">
                            @if (isset($paper->image_path))
                                <img src="{{ $paper->image_path }}" alt="" style="height: 400px;">
                            @endif

                        </div>
                        <div class="heading-news mb-30 pt-30">
                            @isset($paper->title)
                                <h3>{{ $paper->title }}</h3>
                            @endisset
                        </div>

                        <div class="col-md-12">
                            @isset($paper->conten)
                                {!! $paper->conten !!}
                            @endisset
                        </div>

                        <div class="social-share pt-30">
                            <div class="section-tittle">
                                <h3 class="mr-20">Tags:</h3>
                                <ul>
                                    @if ($paper && $tags = $paper->to_tag()->getResults())
                                        @foreach ($tags as $tag)
                                            <li><a href="{{ route('front_tag_view', ['value' => $tag->value]) }}"
                                                    class="btn btn-sm btn-info">{{ $tag->value }}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- From -->
                    <div class="row">
                        <div class="col-lg-8">
                            <form class="form-contact contact_form mb-80" action="contact_process.php" method="post"
                                id="contactForm" novalidate="novalidate">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control w-100 error" name="message" id="message" cols="30" rows="9"
                                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder="Enter Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input class="form-control error" name="name" id="name" type="text"
                                                onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Enter your name'" placeholder="Enter your name">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input class="form-control error" name="email" id="email" type="email"
                                                onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Enter email address'" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input class="form-control error" name="subject" id="subject" type="text"
                                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'"
                                                placeholder="Enter Subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit"
                                        class="button button-contactForm boxed-btn boxed-btn2">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Flow Socail -->
                    <div class="single-follow mb-45">
                        <div class="single-box">
                            <div class="follow-us d-flex align-items-center">
                                <div class="follow-social">
                                    <a href="#"><img src="assets/img/news/icon-fb.png" alt=""></a>
                                </div>
                                <div class="follow-count">
                                    <span>8,045</span>
                                    <p>Fans</p>
                                </div>
                            </div>
                            <div class="follow-us d-flex align-items-center">
                                <div class="follow-social">
                                    <a href="#"><img src="assets/img/news/icon-tw.png" alt=""></a>
                                </div>
                                <div class="follow-count">
                                    <span>8,045</span>
                                    <p>Fans</p>
                                </div>
                            </div>
                            <div class="follow-us d-flex align-items-center">
                                <div class="follow-social">
                                    <a href="#"><img src="assets/img/news/icon-ins.png" alt=""></a>
                                </div>
                                <div class="follow-count">
                                    <span>8,045</span>
                                    <p>Fans</p>
                                </div>
                            </div>
                            <div class="follow-us d-flex align-items-center">
                                <div class="follow-social">
                                    <a href="#"><img src="assets/img/news/icon-yo.png" alt=""></a>
                                </div>
                                <div class="follow-count">
                                    <span>8,045</span>
                                    <p>Fans</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- New Poster -->
                    <div class="news-poster d-none d-lg-block">
                        <img src="assets/img/news/news_card.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About US End -->
@endsection

@section('morning_post')
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Nav Card -->
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane active" id="{{ 'nav-' . 'after-detail' }}" role="tabpanel"
                            aria-labelledby="{{ 'nav-' . 'after-detail' . '-tab' }}">
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
                                                    <p>{{ $paper_first->short_conten }}</p>
                                                    <span>by
                                                        {{ $paper_first->to_writer()->getResults() ? $paper_first->to_writer()->getResults()->name : '' }}
                                                        -
                                                        {{ date('M d, Y', strtotime($paper_first->updated_at)) }}</span>
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
                                                            <span
                                                                class="colorb">{{ $paper->to_writer()->getResults() ? $paper->to_writer()->getResults()->name : '' }}
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
        </div>
    </div>
@endsection
