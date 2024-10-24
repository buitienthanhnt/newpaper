@inject('routerHelper', 'App\Helper\RouterHelper')
<div class="most-recent-area">
    <div class="small-tittle mb-20">
        <h4>{{ $title }}</h4>
    </div>
    @if ($most_recent && ($first_recent = $most_recent->first()))
        <div class="most-recent mb-40">
            <div class="most-recent-img" style="max-height: 400px; object-fit: cover">
                <img src="{{ $first_recent->getImagePath() }}" alt="">
                <div class="most-recent-cap">
                    <span
                        class="bgbeg">{{ $first_recent->getPaperCategories()->first()->toCategory()->first()->{\App\Models\CategoryInterface::ATTR_NAME} }}</span>
                    <h4><a href="{{ $routerHelper->paperDetailUrl($first_recent) }}">{{ $first_recent->{App\Models\PaperInterface::ATTR_TITLE} }}
                    </h4>
                    <p>{{ $first_recent->writerName() }}
                        | {{ $first_recent->getUpdatedAt() }}</p>
                </div>
            </div>
        </div>
        @if ($af_recents = $most_recent->diff([$most_recent->first()]))
            @foreach ($af_recents as $af_recent)
                <div class="most-recent-single">
                    <div class="most-recent-images">
                        <img src="{{ $af_recent->getImagePath() }}" style="width: 85px; height: 79px;" alt="">
                    </div>
                    <div class="most-recent-capt">
                        <h4><a
                                href="{{ $routerHelper->paperDetailUrl($af_recent) }}">{{ $af_recent->{App\Models\PaperInterface::ATTR_TITLE} }}</a>
                        </h4>
                        <p>{{ $af_recent->writerName() }}
                            | {{ $af_recent->getUpdatedAt() }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</div>
