@extends('frontend/layouts/category_view')
@inject('DomHtml', 'App\Helper\HelperFunction')

@section('page_title')
{{ $tag }}
@endsection

@section('trending_left')
    @render(App\ViewBlock\TrendingLeft::class)
@endsection

@section('trending_right')
    @render(App\ViewBlock\TrendingRight::class)
@endsection

{{-- ========================new_post============================ --}}

{{-- col-lg-8 left --}}
@section('new_post_left')
<div class="whats-news-wrapper">

    <!-- Heading & Nav Button -->
    <div class="row justify-content-between align-items-end mb-15">
        <div class="col-md-12">
            <div class="section-tittle mb-30">
                <h3>{{ 'Tags: '.$tag }}</h3>
            </div>
        </div>

    </div>
    <!-- Heading & Nav Button -->

    <!-- Tab content -->
    <div class="row">
        <div class="col-12">
            <!-- Nav Card -->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane active" id="{{ 'nav-' . $tag }}" role="tabpanel"
                    aria-labelledby="{{ 'nav-' . $tag . '-tab' }}">
                    <div class="row">
                        <!-- Right single caption -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="whats-news-single mb-20">
                                <!-- single -->
                                @foreach (\App\Models\PaperTag::getPaperByTags($tag) as $paper)
                                <div class="row">
                                    <div class="whats-right-single mb-10">
                                        <div class="col-md-6">
                                            <img src="{{ $paper->getImagePath() }}" class="whates-img"
                                                style="width: 100%; height: auto;" alt="">
                                        </div>
                                        <div class="col-md-6 whats-right-cap">
                                            <h4>
                                                <a
                                                    href="{{ route('front_paper_detail', ['alias' => $paper->url_alias, 'paper_id' => $paper->id]) }}">
                                                    <h4>
                                                        {{ $paper->title }}
                                                    </h4>
                                                    {{ $paper->short_conten }}
                                                </a>
                                            </h4>
                                            <span class="colorb">{{ $paper->writerName() }}
                                            </span>
                                            <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Nav Card -->
        </div>
    </div>
    <!-- Tab content -->
</div>
@endsection

@section('new_post_banner')

@endsection
{{-- col-lg-8 --}}

@section('weekly3_news')

@endsection

@section('weekly2_news')

@endsection

{{-- =====================new_post=============================== --}}
{{-- row --}}
@section('banner_last_conten')
<div class="col-lg-10 col-md-10">
    <div class="banner-one">
        <img src={{ $DomHtml->getConfig('last_conten_image') ?:
        asset('assets/frontend/img/gallery/body_card3.png') }}
        width={{ $DomHtml->getConfig('home_image_width', 944)}}
        height={{ $DomHtml->getConfig('home_image_height', 152)}} />
    </div>
</div>
@endsection
{{-- row --}}
{{-- =====================banner_last=============================== --}}
