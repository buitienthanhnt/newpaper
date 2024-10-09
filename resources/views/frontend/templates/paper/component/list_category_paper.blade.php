@if ($papers)
    @foreach ($papers as $paper)
        <div class="row">
            <div class="whats-right-single mb-10">
                <div class="col-md-6">
                    <img src="{{ $paper->getImagePath() }}" class="whates-img" style="width: 100%; height: auto;"
                        alt="">
                </div>
                <div class="col-md-6 whats-right-cap">
                    <h4>
                        <a href="{{ route('front_paper_detail', ['alias' => $paper->url_alias, 'paper_id' => $paper->id]) }}">
                            <h4 class="text-info">
                                {{ $paper->title }}
                            </h4>
                            {{ $paper->short_conten }}
                        </a>
                    </h4>
                    <span
                        class="colorb" style="color: #ff2143">{{ $paper->writerName() }}
                    </span>
                    <p>{{ date('M d, Y', strtotime($paper->updated_at)) }}
                        <a href="" class="text text-info" style="float: right;"><i class="fa fa-eye"></i>
                            {{ $paper->viewCount() }}</a>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@endif
