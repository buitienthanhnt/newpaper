<div class="weekly3-news-active dot-style d-flex">
	@if ($weekly3_contens)
		@foreach ($weekly3_contens as $weekly3_conten)
			<div class="weekly3-single">
				<div class="weekly3-img">
					<img src="{{ $weekly3_conten->image_path }}" alt="">
				</div>
				<div class="weekly3-caption">
					<h4><a href="latest_news.html">{{ $weekly3_conten->title }}</a></h4>
					<p>{{ date('M d, Y', strtotime($weekly3_conten->updated_at)) }}</p>
				</div>
			</div>
		@endforeach
	@endif
</div>