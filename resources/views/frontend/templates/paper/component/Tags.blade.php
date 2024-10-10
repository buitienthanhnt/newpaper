@if ($paper && $paper->get_tags()->count())
    <div class="section-tittle">
        <h3 class="mr-20">Tags:</h3>
        <ul>
            @foreach ($paper->get_tags() as $tag)
                <li>
                    <a href="{{ route('front_tag_view', ['value' => $tag->value]) }}"
                        class="btn btn-info">{{ $tag->value }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
