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
                                <h4>
                                    <a href="{{ route('front_paper_detail', ['paper_id' => $popular_item->id, 'alias' => $popular_item->url_alias]) }}">
                                        {{ $popular_item->title }}
                                    </a>
                                </h4>
                                <p>{{ $popular_item->writerName() }}| 2 hours ago</p>
                            </div>
                        </div>
                    @endforeach
                @endif
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
