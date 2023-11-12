@isset($topcategory)
    @if ($topcategory)
        <ul @php
if (!isset($lever)) {
            $lever = 0;
        } @endphp
            @if ($lever == 0) id="navigation"
    @else
        @if ($lever == 1)
            class="submenu"
        @else
            class="submenu chilMenu" @endif
            @endif
            >
            @foreach ($topcategory as $item)
                <li>
                    <a href="{{ route('front_category', ['category' => $item->url_alias]) }}">{{ $item->name }}</a>
                    @if (count($childrent = $item->getChildrent()))
                        {!! view('frontend.templates.share.topElement', ['topcategory' => $childrent, 'lever' => $lever + 1]) !!}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

@endisset
