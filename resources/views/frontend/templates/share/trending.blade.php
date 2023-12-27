<div class="col-12">
	<div class="recent-active dot-style d-flex dot-style">
		@if ($trendings)
			@foreach ($trendings as $trending)
				<!-- Single -->
				<div class="single-recent">
					<div class="what-img">
						<img src="{{ $trending->image_path }}" alt="">
					</div>
					<div class="what-cap">
						<h4><a href="#">
								<h4><a href="latest_news.html">{{ $trending->title }}</a></h4>
							</a></h4>
						<P>{{ date('M d, Y', strtotime($trending->updated_at)) }}</P>
						<a class="popup-video btn-icon" href="https://www.youtube.com/watch?v=1aP-TXUpNoU"><span
								class="flaticon-play-button"></span></a>

					</div>
				</div>
			@endforeach
		@endif
	</div>
</div>