@foreach ($trending_right as $tren_r)
    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="trending-top mb-30">
            <div class="trend-top-img" style="max-height: 210px">
                <img src="{{ $tren_r->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}"
                    style="max-height: 215px; min-height: 180px; object-fit: cover" alt="">
                <div class="trend-top-cap trend-top-cap2">
                    <span
                        class="bgg">{{ $tren_r->getPaperCategories()->first()
                            ? $tren_r->getPaperCategories()->first()->for_category()->first()->name
                            : '' }}
                    </span>
                    <div style="background-color: #cccccc; opacity: 0.6; border-radius: 5px 0px 0px 5px; padding-left: 4px">
                        <a style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;"
                            href="{{ route('front_paper_detail', ['paper_id' => $tren_r->id, 'alias' => $tren_r->url_alias]) }}">
                            <h3>
                                {{ $tren_r->title }}
                            </h3>
                        </a>
                        <p style="margin-bottom: 0px; color: blueviolet">by {{ $tren_r->writerName() }}
                            -
                            {{ date('M d, Y', strtotime($tren_r->updated_at)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
