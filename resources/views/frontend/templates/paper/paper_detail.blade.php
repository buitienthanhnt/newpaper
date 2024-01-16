@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
    <script src="{{ asset('assets/frontend/js/commentReply.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/commentHistory.js') }}"></script>
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
    <link rel="stylesheet" href={{ asset('assets/frontend/css/paper/detail.css') }}>
    <style>
        .paper_action {color: #13e6ed}
        .paper_action.checked {color: #cc18b4}
    </style>
@endsection

@section('main_conten')
    <!-- About US Start -->
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Trending Tittle -->
                    <div id="detail_main_conten" class="about-right mb-90">
                        <div class="about-img">
                            @if (isset($paper->image_path))
                                <img src="{{ $paper->image_path ?: asset('assets/pub_image/defaul.PNG') }}"
                                    style="height: 400px;">
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
                            {!! view('frontend.templates.paper.component.Tags', ['paper' => $paper])->render() !!}
                        </div>
                        <div class="col-md-12">
                            <span class="float-right">
                                <i class="fa fa-thumbs-up paper_action like"> 12</i>  &nbsp;
                                <i class="fa fa-heart paper_action heart"> 8</i> 
                            </span>
                        </div>
                    </div>
                    <!-- From -->
                    <div class="col-md-12 bootstrap snippets">
                        {!! view('frontend.templates.paper.component.commentForm', ['paper' => $paper])->render() !!}
                        <div class="panel" id="commentHistory">
                            {!! view('frontend.templates.paper.component.commentHistory', ['comments' => $paper->getComments()])->render() !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- Flow Socail -->
                    {!! view('frontend.templates.share.social')->render() !!}
                    <!-- New Poster -->
                    {{-- <div class="news-poster d-none d-lg-block"> --}}
                    {{-- <img src="assets/img/news/news_card.jpg" > --}}
                    {{-- </div> --}}

                    {{-- most of view --}}
                    {!! view('frontend.templates.paper.component.mostViewDetail')->render() !!}
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
                                                    <img src="{{ $paper_first->image_path }}">
                                                </div>
                                                <div class="whates-caption">
                                                    <h4><a
                                                            href="{{ route('front_page_detail', ['alias' => $paper_first->url_alias, 'page' => $paper_first->id]) }}">{{ $paper_first->title }}</a>
                                                    </h4>
                                                    <p>{{ $paper_first->short_conten }}</p>
                                                    <span>by
                                                        {{ $paper_first->writerName() }}
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
                                        @if ($top_paper && $papers)
                                            @foreach ($papers as $paper)
                                                <div class="row">
                                                    <div class="whats-right-single mb-10">
                                                        <div class="col-md-6">
                                                            <img src="{{ $paper->image_path }}" class="whates-img"
                                                                style="width: 100%; height: auto;">
                                                        </div>
                                                        <div class="col-md-6 whats-right-cap">
                                                            <h4>
                                                                <a
                                                                    href="{{ route('front_page_detail', ['alias' => $paper->url_alias, 'page' => $paper->id]) }}">
                                                                    <h4>{{ $paper->title }}</h4>
                                                                    {{ $paper->short_conten }}
                                                                </a>
                                                            </h4>
                                                            <span class="colorb">{{ $paper->writerName() }}</span>
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

    <script>
        var reply_comment_url = '{{ route('paper_reply_comment') }}';
        var like_url = '{{ route('paper_like') }}';
        var token = "{{ csrf_token() }}";
        var paper_value = "{{ $paper->id }}";
        $(document).ready(function() {
            $('.paper_action').click(function() {
                if ($(this).hasClass('checked')) {
                    $(this).html(' '+ (Number($(this).html()) - 1));
                    $(this).removeClass('checked');
                    return;
                }
                $(this).html(' ' + (Number($(this).html()) + 1));
                $(this).addClass('checked');
            })
        })
    </script>
@endsection
