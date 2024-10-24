@extends('frontend/layouts/category_view')
@inject('DomHtml', 'App\Helper\HelperFunction')

@section('page_title')
    {{ $category->name }}
@endsection

@section('weekly2_conten')
    {!! view('frontend.templates.homePage.mostPopulator') !!}
@endsection

@section('weekly3_conten')
    {!! view('frontend.templates.homePage.mostLike') !!}
@endsection

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
                                            <a class="text-info"
                                                href="{{ route('front_paper_detail', ['alias' => $paper_first->url_alias, 'paper_id' => $paper_first->id]) }}">
                                                <h4
                                                    style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                                    {{ $paper_first->title }}
                                                </h4>
                                            </a>
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
                                                            <h4 class="text-info"
                                                                style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                                                {{ $paper->title }}</h4>
                                                        </a>
                                                    </h4>
                                                    <h6
                                                        style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical;">
                                                        {{ $paper->short_conten }}</h6>
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

@section('flow_socail')
    @render(App\ViewBlock\Frontend\Social::class)
@endsection

@section('most_recent')
    @render(App\ViewBlock\MostRecent::class)
@endsection

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
    <script type="text/javascript">
        var url = "{{ route('front_load_more') }}";
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
    </script>
@endsection
