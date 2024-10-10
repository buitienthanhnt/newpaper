@foreach ($trending_right as $tren_r)
    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="trending-top mb-30">
            <div class="trend-top-img" style="max-height: 210px">
                <img src="{{ $tren_r->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}" style="max-height: 210px; object-fit: cover" alt="">
                <div class="trend-top-cap trend-top-cap2">
                    <span
                        class="bgg">{{ $tren_r->getPaperCategories()->first()
                            ? $tren_r->getPaperCategories()->first()->for_category()->first()->name
                            : '' }}</span>
                    <h2><a
                            href="{{ route('front_paper_detail', ['paper_id' => $tren_r->id, 'alias' => $tren_r->url_alias]) }}">{{ $tren_r->title }}</a>
                    </h2>
                    <p>by {{ $tren_r->writerName() }}
                        -
                        {{ date('M d, Y', strtotime($tren_r->updated_at)) }}</p>
                </div>
            </div>
        </div>
    </div>
@endforeach
