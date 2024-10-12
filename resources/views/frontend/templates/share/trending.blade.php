<div class="section-tittle mb-30 mt-20 ml-15">
    <h3>Trending News</h3>
</div>
    <div class="recent-active dot-style d-flex dot-style">
        @if ($trendings)
            @foreach ($trendings as $trending)
                <!-- Single -->
                <div class="single-recent">
                    <div class="what-img">
                        <img src="{{ $trending->getImagePath() }}" alt="">
                    </div>
                    <div class="what-cap" style="background-color: transparent">
                        <h4>
							<a
                                href="">
                            	<h4>
									<a href="{{ route('front_paper_detail', ['paper_id' => $trending->id, 'alias' => $trending->url_alias]) }}">{{ $trending->title }}</a>
								</h4>
                            </a>
						</h4>
                        <P>{{ date('M d, Y', strtotime($trending->updated_at)) }}</P>
                        <a class="popup-video btn-icon" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
                                class="flaticon-play-button"></span></a>
                    </div>
                </div>
            @endforeach
        @endif
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
