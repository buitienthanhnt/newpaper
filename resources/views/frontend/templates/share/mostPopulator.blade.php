@inject('DomHtml', 'App\Helper\HelperFunction')
<div class="weekly2-news-area pb-30 gray-bg">
    <div class="container">
        <div class="weekly2-wrapper">
            <div class="row">
                <div class="col-lg-3">
                    <div class="home-banner2 d-none d-lg-block">
                        <img
                            src={{ $DomHtml->getConfig('weekly_image') ?: asset('assets/frontend/img/gallery/body_card2.png') }}
                                width={{ $DomHtml->getConfig('home_image_width', 264) }}
                                height={{ $DomHtml->getConfig('home_image_height', 354) }} />
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="slider-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="small-tittle mb-30">
                                    <h4>Most Popular</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="weekly2-news-active d-flex">
                                    <!-- Single -->
                                    @if ($most_popular)
                                        @foreach ($most_popular as $popular_item)
                                            <div class="weekly2-single">
                                                <div class="weekly2-img" style="max-height: 275px">
                                                    <img style="max-height: 240px; object-fit: cover"
                                                         src="{{ $popular_item->getImagePath() }}" alt="">
                                                </div>
                                                <div class="weekly2-caption">
                                                    <a href="{{ route('front_paper_detail', ['paper_id' => $popular_item->id, 'alias' => $popular_item->url_alias]) }}">
                                                        <h4 style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                                            {{ $popular_item->title }}
                                                        </h4>
                                                    </a>
                                                    <p>{{ $popular_item->writerName() }}| 2 hours ago</p>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.weekly2-news-active').slick({
        dots: false,
        infinite: true,
        speed: 500,
        arrows: true,
        autoplay: true,
        loop: true,
        slidesToShow: 3,
        prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                dots: false,
            }
        },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 700,
                settings: {
                    arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>
