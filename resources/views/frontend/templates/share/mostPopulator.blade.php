<div class="slider-wrapper">
    <!-- section Tittle -->
    <div class="row">
        <div class="col-lg-12">
            <div class="small-tittle mb-30">
                <h4>Most Popular</h4>
            </div>
        </div>
    </div>
    <!-- Slider -->
    <div class="row">
        <div class="col-lg-12">
            <div class="weekly2-news-active d-flex">
                <!-- Single -->
                @if ($most_popular)
                    @foreach ($most_popular as $popular_item)
                        <div class="weekly2-single">
                            <div class="weekly2-img">
                                <img src="{{ $popular_item->image_path }}" alt="">
                            </div>
                            <div class="weekly2-caption">
                                <h4><a
                                        href="{{ route('front_page_detail', ['page' => $popular_item->id, 'alias' => $popular_item->url_alias]) }}">{{ $popular_item->title }}</a>
                                </h4>
                                <p>{{ $popular_item->to_writer()->getResults() ? $popular_item->to_writer()->getResults()->name : '' }}
                                    | 2 hours ago</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
