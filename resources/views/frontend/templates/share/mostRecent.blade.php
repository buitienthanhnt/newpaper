<div class="most-recent-area">
    <!-- Section Tittle -->
    <div class="small-tittle mb-20">
        <h4>Most Recent</h4>
    </div>
    <!-- Details -->
    @if ($most_recent && ($first_recent = $most_recent->first()))
        <div class="most-recent mb-40">
            <div class="most-recent-img" style="max-height: 400px; object-fit: cover">
                <img src="{{ $first_recent->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}" alt="">
                <div class="most-recent-cap">
                    <span class="bgbeg">Vogue</span>
                    <h4><a
                            href="{{ route('front_paper_detail', ['page' => $first_recent->id, 'alias' => $first_recent->url_alias]) }}">What
                            to Wear: 9+ Cute Work <br>
                            Outfits to Wear This.</a></h4>
                    <p>{{ $first_recent->writerName() }}
                        | 2 hours ago</p>
                </div>
            </div>
        </div>
        @if ($af_recents = $most_recent->diff([$most_recent->first()]))
            @foreach ($af_recents as $af_recent)
                <div class="most-recent-single">
                    <div class="most-recent-images">
                        <img src="{{ $af_recent->getImagePath() ?: asset('assets/pub_image/defaul.PNG') }}"
                            style="width: 85px; height: 79px;" alt="">
                    </div>
                    <div class="most-recent-capt">
                        <h4><a
                                href="{{ route('front_paper_detail', ['page' => $af_recent->id, 'alias' => $af_recent->url_alias]) }}">{{ $af_recent->title }}</a>
                        </h4>
                        <p>{{ $af_recent->writerName() }}
                            | 2 hours ago</p>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</div>
