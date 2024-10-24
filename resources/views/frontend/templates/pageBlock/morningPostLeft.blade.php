<div class="slider-active">
    @if ($trending_left)
        @foreach ($trending_left as $tren)
            <div class="single-slider">
                <div class="trending-top mb-30">
                    <div class="trend-top-img">
                        <img style="max-height: 465px; object-fit: cover;"
                            src="{{ $tren->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}" alt="">
                        <div class="trend-top-cap">
                            @if ($first_category = $tren->getPaperCategories()->first()?->toCategory()->first())
                                <a href="{{$first_category->getUrl()}}">
                                    <span class="bgr" data-animation="fadeInUp" data-delay=".2s"
                                        data-duration="1000ms">{{ $first_category->{\App\Models\CategoryInterface::ATTR_NAME} }}</span>
                                </a>
                            @endif
                            <h2><a href="{{ $tren->getUrl() }}" data-animation="fadeInUp"
                                    data-delay=".4s"
                                    data-duration="1000ms">{{ $tren->{\App\Models\PaperInterface::ATTR_TITLE} }}</a>
                            </h2>
                            <p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
                                {{ $tren->writerName() }} -
                                {{ date('M d, Y', strtotime($tren->updated_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
