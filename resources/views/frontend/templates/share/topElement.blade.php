@isset($topcategory)
    @if ($topcategory)
        <ul @php if (!isset($lever)) {$lever = 0;} @endphp
            @if ($lever == 0) id="navigation"
            @else
                @if ($lever == 1) class="submenu"
                @else class="submenu chilMenu"
                @endif
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
            @if($lever == 0)
                <li>
                    <a class="text-info" href="{{ route('paper_byType', ['type' => "price"]) }}">mua bán <i class="fa fa-cloud"></i></a>
                </li>
                <li class="d-block d-md-none" style="float: right">
                    @if (Auth::check())
                        <a class="text-info" style="float: right" href="{{ route('user_logout') }}">
                            <i class="fa fa-sign-out-alt"></i>&nbsp; LogOut
                        </a>
                    @else
                        <a class="text-info" style="float: right" href="{{ route('user_login') }}">
                            <i class="fa fa-user"></i>&nbsp; LogIn
                        </a>
                    @endif
                        <form action="{{ route('search_all') }}">
                            <input type="text" name="search" placeholder="Searching key" required>
                            <button type="submit" style="background-color: unset; border-width: inherit">
                                <i class="fa fa-search text-info"></i>
                            </button>
                        </form>
                </li>
            @endif
        </ul>
    @endif
@endisset
