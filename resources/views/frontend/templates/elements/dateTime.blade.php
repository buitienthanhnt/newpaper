<div>
    by
    <a href="" class="text text-info">
        {{ $paper->writerName() }}
    </a>
    -
    {{ date('M d, Y', strtotime($paper->updated_at ?? '')) }}

    <a class="text text-info" style="float: right;">
        @if ($count = $paper->commentCount())
            {{ $count }} <i class="fa fa-comment"></i> |
        @endif
        {!! view('frontend.templates.elements.viewCount', ['paper' => $paper])->render() !!}
    </a>
</div>
