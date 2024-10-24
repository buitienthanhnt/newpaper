@extends('frontend.layouts.home_default')
@inject('DomHtml', 'App\Helper\HelperFunction')

@render(App\ViewBlock\Frontend\Message::class)

@section('weekly2_news')
    {!! view('frontend.templates.homePage.mostPopulator') !!}
@endsection

@section('articles')
    {!! view('frontend.templates.homePage.mostTrending') !!}
@endsection

@section('weekly3_news')
    {!! view('frontend.templates.homePage.mostLike') !!}
@endsection

@if ($video_contens)
    @section('video_area_conten')
        <div class="youtube-area video-padding d-none d-sm-block">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="video-items-active">
                            @foreach ($video_contens as $video)
                                <div class="video-items text-center">
                                    <iframe class="w-100" style="height: 520px" src="{{ $video['url'] }}"></iframe>
                                    {{-- <video controls>
                                    <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                                    Your browser does not support the video tag.
                                </video> --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="video-info">
                    <div class="row">
                        <div class="col-12">
                            <div class="testmonial-nav text-center">
                                @if ($video_contens)
                                    @foreach ($video_contens as $video)
                                        <div class="single-video" style="padding-top: 10px;">
                                            <iframe class="w-100" src="{{ $video['url'] }}"></iframe>
                                            {{-- <video controls>
                                            <source src={{ asset('assets/frontend/video/news2.mp4') }} type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video> --}}
                                            <div class="video-intro">
                                                <h4>{{ $video['title'] }}</h4>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif

@section('banner_last_conten')
    <div class="col-lg-10 col-md-10">
        <div class="banner-one">
            <img src={{ $DomHtml->getConfig('last_conten_image') ?: asset('assets/frontend/img/gallery/body_card3.png') }}
                width={{ $DomHtml->getConfig('home_image_width', 944) }}
                height={{ $DomHtml->getConfig('home_image_height', 152) }} />
        </div>
    </div>
@endsection