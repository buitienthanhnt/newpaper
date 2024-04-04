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
        .paper_action {
            color: #13e6ed
        }

        .paper_action.checked {
            color: #cc18b4
        }

        .fa-arrow-down {
            color: #13e6ed
        }

        .fa-arrow-down:hover {
            color: #cc18b4
        }
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
                                    style="max-height: 600px; object-fit: cover">
                            @endif
                        </div>
                        <div class="heading-news mb-30 pt-30">
                            @isset($paper->title)
                                <h3>{{ $paper->title }}</h3>
                            @endisset
                        </div>

                        @if ($sliderImages = $paper->sliderImages())
                            <div class="bd-example">
                                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @for ($i = 0; $i < count($sliderImages); $i++)
                                            <li data-target="#carouselExampleCaptions" data-slide-to="{{ $i }}"
                                                class="{{ $i === 0 ? 'active' : ' ' }}"></li>
                                        @endfor
                                    </ol>

                                    <div class="carousel-inner">
                                        @php
                                            $j = 0;
                                        @endphp
                                        @foreach ($sliderImages as $item)
                                            <div class="carousel-item {{ $j === 0 ? 'active' : ' ' }}">
                                                <img src="{{ $item->value }}" style="width: 100%; height: 560px;"
                                                    alt="">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>{{ $item->title }}</h5>
                                                    <p>{{ $item->description }}</p>
                                                </div>
                                            </div>
                                            @php
                                                $j += 1;
                                            @endphp
                                        @endforeach

                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            @isset($paper->conten)
                                {!! $paper->conten !!}
                            @endisset
                        </div>
                        <div class="social-share pt-30">
                            {!! view('frontend.templates.paper.component.Tags', ['paper' => $paper])->render() !!}
                        </div>
                        {!! view('frontend.templates.paper.component.like', ['paper' => $paper])->render() !!}
                    </div>
                    <!-- From -->
                    <div class="col-md-12 bootstrap snippets">
                        {!! view('frontend.templates.paper.component.commentForm', ['paper' => $paper])->render() !!}
                        <div class="panel" id="commentHistory" data-p="0">
                            {{-- {!! view('frontend.templates.paper.component.commentHistory', ['comments' => $paper->getComments()])->render() !!} --}}
                        </div>
                        @if ($paper->commentCount())
                            <center id="comment-load">
                                <i class="fa fa-arrow-down"></i>
                            </center>
                        @endif
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
    {!! view('frontend.templates.share.populatorContainer') !!}
@endsection

<script>
    var token = "{{ csrf_token() }}";
    var baseUrl = '{{ route('/') }}';
    var reply_comment_url = '{{ route('paper_reply_comment') }}';
    var like_url = '{{ route('paper_like') }}';
    var addLike_url = '{{ route('paper_addLike', ['paper_id' => $paper->id]) }}';
    var paper_value = "{{ $paper->id }}";
    var mostPopulatorUrl = "{{ route('mostPopulator') }}";
    var likeMost = "{{ route('likeMost') }}";
    var trending = "{{ route('trending') }}";
    $(document).ready(function() {
        $('.paper_action').click(function() {
            let type = $(this).hasClass('like') ? 'like' : 'heart';
            if ($(this).hasClass('checked')) {
                $.ajax({
                    url: addLike_url,
                    type: "POST",
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: token,
                        action: 'sub',
                        type: type
                    }),
                    success: function(result) {
                        console.log(result);
                        $(this).html(' ' + (Number($(this).html()) - 1));
                        $(this).removeClass('checked');
                    }.bind(this),
                });
                return;
            }
            $.ajax({
                url: addLike_url,
                type: "POST",
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: token,
                    action: 'add',
                    type: type
                }),
                success: function(result) {
                    console.log(result);
                    $(this).html(' ' + (Number($(this).html()) + 1));
                    $(this).addClass('checked');
                }.bind(this)
            });
        })

        $("#comment-load").click(function() {
            let p = Number($("#commentHistory").attr('data-p')) + 1;
            $.ajax({
                url: baseUrl + '/paper/commentContent/' + paper_value + '/' + p,
                type: "GET",
                success: function(result) {
                    $("#commentHistory").append(result).attr('data-p', p + 1);
                },
            });
        })

        $.ajax({
            url: baseUrl + '/paper/commentContent/' + paper_value +
                `/${$("#commentHistory").attr('data-p')}`,
            type: "GET",
            success: function(result) {
                $("#commentHistory").html(result);
            },
        });
    })
</script>

@section('js_after')
    <script src={{ asset('assets/frontend/js/mostPopulator.js') }}></script>
@endsection
