<div class="recent-articles">
    <div class="container">
        <div class="recent-wrapper">
            <div class="col-md-12 pt-20">
                <div class="section-tittle mb-30 mt-20 ml-15">
                    <h3>Trending News</h3>
                </div>
                <div class="recent-active dot-style d-flex dot-style">
                @if ($trendings)
                    @foreach ($trendings as $trending)
                        <!-- Single -->
                            <div class="single-recent">
                                <div class="what-img">
                                    <img src="{{ $trending->getImagePath() }}" style="max-height: 240px" alt="">
                                </div>
                                <div class="what-cap">
                                    <a href="{{ route('front_paper_detail', ['paper_id' => $trending->id, 'alias' => $trending->url_alias]) }}">
                                        <h4 style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $trending->title }}
                                        </h4>
                                    </a>
                                    <P>{{ date('M d, Y', strtotime($trending->updated_at)) }}</P>
                                    <a class="popup-video btn-icon"
                                       href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                                            class="flaticon-play-button"></span></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.recent-active').slick({
        dots: false,
        infinite: true,
        speed: 600,
        arrows: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="slick-prev"> <span class="flaticon-arrow"></span></button>',
        nextArrow: '<button type="button" class="slick-next"> <span class="flaticon-arrow"><span></button>',
        initialSlide: 3,
        loop: true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
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
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>
