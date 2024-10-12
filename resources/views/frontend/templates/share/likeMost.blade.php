<div class="section-tittle mb-30 mt-20 ml-15">
    <h3>like most</h3>
</div>
<div class="weekly3-news-active dot-style d-flex">
    @if ($weekly3_contens)
        @foreach ($weekly3_contens as $weekly3_conten)
            <div class="weekly3-single">
                <div class="weekly3-img">
                    <img src="{{ $weekly3_conten->getImagePath() }}" alt="">
                </div>
                <div class="weekly3-caption">
                    <h4><a
                            href="{{ route('front_paper_detail', ['paper_id' => $weekly3_conten->id, 'alias' => $weekly3_conten->url_alias]) }}">{{ $weekly3_conten->title }}</a>
                    </h4>
                    <p>{{ date('M d, Y', strtotime($weekly3_conten->updated_at)) }}</p>
                </div>
            </div>
        @endforeach
    @endif
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
    $(".slick-dots").css('bottom', '0px');
</script>
