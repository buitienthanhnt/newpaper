<div class="slider-active">
	@if ($trending_left)
		@foreach ($trending_left as $tren)
			<div class="single-slider">
				<div class="trending-top mb-30">
					<div class="trend-top-img">
						<img src="{{ $tren->image_path ?: asset('assets/pub_image/defaul.PNG') }}" alt="">
						<div class="trend-top-cap">
							@if ($first_category = $tren->to_category()->first())
								<a href="">
									<span class="bgr" data-animation="fadeInUp" data-delay=".2s"
										data-duration="1000ms">{{ $first_category->for_category()->first()->name }}</span>
								</a>
							@endif
							<h2><a href="{{ route('front_page_detail', ['page' => $tren->id, 'alias' => $tren->url_alias]) }}"
									data-animation="fadeInUp" data-delay=".4s"
									data-duration="1000ms">{{ $tren->title }}</a>
							</h2>
							<p data-animation="fadeInUp" data-delay=".6s" data-duration="1000ms">by
								{{ $tren->to_writer()->getResults() ? $tren->to_writer()->getResults()->name : '' }} -
								{{ date('M d, Y', strtotime($tren->updated_at)) }}</p>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@endif
</div>