@if ($papers)
    @foreach ($papers as $paper)
        <div class="row">
            <div class="whats-right-single mb-10">
                <div class="col-md-6">
                    <img src="{{ $paper->image_path }}" class="whates-img" style="width: 100%; height: auto;"
                        alt="">
                </div>
                <div class="col-md-6 whats-right-cap">
                    <h4>
                        <a href="{{ route('front_page_detail', ['alias' => $paper->url_alias, 'page' => $paper->id]) }}">
                            <h4>
                                {{ $paper->title }}
                            </h4>
                            {{ $paper->short_conten }}
                        </a>
                    </h4>
                    <span
                        class="colorb">{{ $paper->to_writer()->getResults() ? $paper->to_writer()->getResults()->name : '' }}
                    </span>
                    <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@endif
