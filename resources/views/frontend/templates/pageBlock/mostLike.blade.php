<div class="weekly3-news-area">
    <div class="container">
        <div class="weekly3-wrapper">
            <div class="row">
                <div class="col-lg-12 pt-20 pb-20 rounded">
                    <div class="slider-wrapper">
                        <div class="col-lg-12">
                            <div class="small-tittle mb-30">
                                <h4>{{ $title }}</h4>
                            </div>
                            <div class="weekly3-news-active dot-style d-flex">
                                @if ($likes)
                                    @foreach ($likes as $like)
                                        <div class="weekly3-single">
                                            <div class="weekly3-img">
                                                <img src="{{ $like->getImagePath() }}" style="max-height: 160px;"
                                                    alt="">
                                            </div>
                                            <div class="weekly3-caption">
                                                <a
                                                    href="{{ $like->getUrl() }}">
                                                    <h4 class="text-2lines">
                                                        {{ $like->{\App\Models\PaperInterface::ATTR_TITLE} }}
                                                    </h4>
                                                </a>
                                                <p>{{ date('M d, Y', strtotime($like->updated_at)) }}</p>
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
<script type="text/javascript">
    $('.weekly3-news-active').slick({
        dots: true,
        infinite: true,
        speed: 500,
        arrows: false,
        autoplay: true,
        loop: true,
        slidesToShow: 4,
        prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
        slidesToScroll: 1,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true,
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
    $(".slick-dots").css('bottom', '-20px');
</script>
