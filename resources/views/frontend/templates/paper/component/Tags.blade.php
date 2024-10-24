@if ($paper && $paper->getTags()->count())
    <div class="section-tittle">
        <h3 class="mr-20">Tags:</h3>
        <ul>
            @foreach ($paper->getTags() as $tag)
                <li>
                    <a class="btn btn-info px-3 py-3" href="{{ $tag->getUrl() }}">{{ $tag->value }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
