@foreach ($trending_right as $tren_r)
    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="trending-top mb-30">
            <div class="trend-top-img" style="max-height: 210px">
                <img src="{{ $tren_r->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}"
                     style="max-height: 215px; min-height: 180px; object-fit: cover" alt="">
                <div class="trend-top-cap trend-top-cap2">
                    @if($trenCategory = $tren_r->getPaperCategories()->first()?->toCategory()->first())
                        <a href="{{$trenCategory->getUrl()}}">
                            <span class="bgg">
                                {{ $trenCategory->{\App\Models\CategoryInterface::ATTR_NAME} }}
                            </span>
                        </a>
                    @endif
                    <div
                        style="background-color: #cccccc; opacity: 0.6; border-radius: 5px 0px 0px 5px; padding-left: 4px">
                        <a class="text-2lines"
                           href="{{ $tren_r->getUrl() }}">
                            <h3 class="text-3lines">
                                {{ $tren_r->{\App\Models\PaperInterface::ATTR_TITLE} }}
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
