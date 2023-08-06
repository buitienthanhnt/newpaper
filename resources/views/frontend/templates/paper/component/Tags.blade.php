<div class="section-tittle">
	<h3 class="mr-20">Tags:</h3>
	<ul>
		@if ($paper && ($tags = $paper->to_tag()->getResults()))
			@foreach ($tags as $tag)
				<li><a href="{{ route('front_tag_view', ['value' => $tag->value]) }}"
						class="btn btn-info">{{ $tag->value }}</a></li>
			@endforeach
		@endif
	</ul>
</div>