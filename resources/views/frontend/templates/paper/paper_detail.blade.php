@extends('frontend.layouts.pagestruct')
@inject('DomHtml', 'App\Helper\HelperFunction')

@section('metas')
    <meta name="title" content="{{ $paper->title }}">
    <meta property="og:image" content="{{ $paper->image_path }}" />
    <meta name="twitter:image" content="{{ $paper->image_path }}">
    <meta name="image" content="{{ $paper->image_path }}">
    <meta property="og:image:height" content="600">
    <meta property="og:image:width" content="600">
@endsection

@section('page_header')
    @render(\App\ViewBlock\TopBar::class)
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection

@section('page_title')
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

@section('head_before_js')
    <script type="text/javascript">
        var flashToken = "<?= csrf_token() ?>"
    </script>
@endsection

@section('main_conten')
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success" id="success-message" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-9">
                    <!-- Trending Tittle -->
                    <div id="detail_main_conten" class="about-right mb-90">
                        <?php /** @var \App\Models\Paper $paper */ ?>
                        @if (!$paper->getPrice())
                            {{-- <div class="about-img">
                                @if (isset($paper->image_path))
                                    <img src="{{ $paper->image_path ?: asset('assets/pub_image/defaul.PNG') }}"
                                        style="max-height: 600px; object-fit: cover">
                                @endif
                            </div> --}}
                        @endif

                        <div class="heading-news mb-30 pt-30">
                            @isset($paper->title)
                                <h3>{{ $paper->title }}</h3>
                            @endisset
                            @isset($paper->short_conten)
                                <h4>{{ $paper->short_conten }}</h4>
                            @endisset
                        </div>
                        <div class="col-md-12">
                            @if (!!$paper->getContents())
                                @foreach ($paper->getContents() as $item)
                                    @switch($item->type)
                                        @case('conten')
                                            {!! $item->value !!}
                                        @break;
                                        @case('slider_data')
                                            @if ($sliderImages = json_decode($item->value, true))
                                                <div class="bd-example">
                                                    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            @for ($i = 0; $i < count($sliderImages); $i++)
                                                                <li data-target="#carouselExampleCaptions"
                                                                    data-slide-to="{{ $i }}"
                                                                    class="{{ $i === 0 ? 'active' : ' ' }}"></li>
                                                            @endfor
                                                        </ol>

                                                        <div class="carousel-inner">
                                                            @php
                                                                $j = 0;
                                                            @endphp
                                                            @foreach ($sliderImages as $i)
                                                                <div class="carousel-item {{ $j === 0 ? 'active' : ' ' }}">
                                                                    <img src="{{ $i['image_path'] }}"
                                                                        style="width: 100%; max-height: 480px;" alt="">
                                                                    <div class="carousel-caption d-none d-md-block">
                                                                        <h5>{{ $i['title'] }}</h5>
                                                                        <p>{{ $i['label'] }}</p>
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $j += 1;
                                                                @endphp
                                                            @endforeach

                                                        </div>
                                                        <a class="carousel-control-prev" href="#carouselExampleCaptions"
                                                            role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carouselExampleCaptions"
                                                            role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @break;
                                        @case('price')
                                            <div class="row">
                                                <div class="col-md-12 p-2">
                                                    <form action="{{ route('front_add_cart') }}" method="post">
                                                        @csrf
                                                        <div class="form-group container row">
                                                            <div class="form-group col-sm-10 row">
                                                                <label class="col-form-label" for="qty">Giá:
                                                                    {{ $paper->getPrice(true) }}
                                                                    vnđ</label>
                                                                <div class="col-sm-3">
                                                                    <input type="number" name="qty" class="form-control"
                                                                        id="qty" min="1" value="1">
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $paper->id }}">
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-sm mb-2">Lưu
                                                                giỏ
                                                                hàng
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @break;
                                        @case('image')
                                            <div class="col-md-12 p-2">
                                                <img src="{{ $item->value }}" style="width: 100%; max-height: 480px;"
                                                    alt="">
                                                <div>

                                                </div>
                                                @isset($item->depend_value)
                                                    <div class="justify-content-center align-items-center d-flex mt-2">
                                                        <span class="text-success"
                                                            style="font-size: 12px; text-decoration: underline">{{ $item->depend_value }}</span>
                                                    </div>
                                                @endisset
                                            </div>
                                        @break

                                        @default
                                    @endswitch
                                @endforeach
                            @endif

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
                    {!! view('frontend.templates.share.social')->render() !!}
                    {{-- most of view --}}
                    {!! view('frontend.templates.paper.component.mostViewDetail')->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('weekly2_news')
    {!! view('frontend.templates.homePage.mostPopulator') !!}
@endsection

@section('weekly3_news')
    {!! view('frontend.templates.homePage.mostLike') !!}
@endsection

@section('js_after')
    <script>
        function BuildUrl() {
		var self = this;

		self.getUrl = function (path = '', params = null) {
			const queryString = new URLSearchParams(params).toString();
			return baseUrl+ '/' + path + '?' + queryString;
		}

		self.getApiUrl = function(path = '', params = null){
			return this.getUrl('api' + '/' + path, params);
		}

		self.token = flashToken;
        return self;

	}

        function sendReply(data) {
            var buildUrl = BuildUrl();
            var element = $(data);
            let comment_id = element.attr("data-comment-id");
            let reply_conten = $("#reply-comment-" + comment_id).val();
            let reply_name = $("#reply-name-" + comment_id).val();
            let reply_email = $("#reply-email-" + comment_id).val();

            $.ajax({
                url: buildUrl.getUrl('paper/commentReply' + "/" + comment_id),
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    _token: buildUrl.token,
                    paper_value: paper_value,
                    conten: reply_conten,
                    name: reply_name,
                    email: reply_email
                }),
                success: function(result) {
                    console.log("success");
                    if (result) {
                        var _data = JSON.parse(result);
                        if (_data.code == 200) {
                            element.parent().parent().children().first().addClass("alert-success").text(
                                "success!").show();
                        } else {
                            element.parent().parent().children().first().addClass("alert-warning").text(
                                "error!").show();
                        }
                    }
                }.bind(this),
                error: function(e) {
                    element.parent().parent().children().first().addClass("alert-warning").text("error!")
                .show();
                }
            })
        }

        setInterval(() => {
            var success_message = $("#success-message");
            if (success_message.length) {
                $(success_message).remove()
            }
        }, 3000);

        var paper_value = "{{ $paper->id }}";
        var addLike_url = '{{ route('front_paper_add_like', ['paper_id' => $paper->id]) }}';
        requirejs([
            'require',
            'viewModal/BuildUrl',
            'viewModal/commentHistory',
            'viewModal/commentReply',
        ], function (require, buildUrl, commentHistory) {
            'use strict';

            $(document).ready(function () {
                $('.paper_action').click(function () {
                    let type = $(this).hasClass('like') ? 'like' : 'heart';
                    if ($(this).hasClass('checked')) {
                        $.ajax({
                            url: buildUrl.getUrl('addLike' + `/${paper_value}`),
                            type: "POST",
                            contentType: 'application/json',
                            data: JSON.stringify({
                                _token: buildUrl.token,
                                action: 'sub',
                                type: type
                            }),
                            success: function (result) {
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
                            _token: buildUrl.token,
                            action: 'add',
                            type: type
                        }),
                        success: function (result) {
                            console.log(result);
                            $(this).html(' ' + (Number($(this).html()) + 1));
                            $(this).addClass('checked');
                        }.bind(this)
                    });
                })

                $("#comment-load").click(function () {
                    let p = Number($("#commentHistory").attr('data-p')) + 1;
                    $.ajax({
                        url: buildUrl.getUrl('paper/commentContent/' + paper_value + '/' +
                            p),
                        type: "GET",
                        success: function (result) {
                            $("#commentHistory").append(result).attr('data-p', p + 1);
                        },
                    });
                })

                $.ajax({
                    url: buildUrl.getUrl('paper/commentContent/' + paper_value +
                        `/${$("#commentHistory").attr('data-p')}`),
                    type: "GET",
                    success: function (result) {
                        $("#commentHistory").html(result);
                    },
                });
            })
        });
    </script>
@endsection
