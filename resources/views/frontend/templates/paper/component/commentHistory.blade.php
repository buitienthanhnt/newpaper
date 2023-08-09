@if (count($comments))
    @foreach ($comments as $item)
        <div class="hpanel">
            <div class="panel-body">
                <div class="media-block pad-all">

                    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Profile Picture"
                            src="https://bootdey.com/img/Content/avatar/avatar1.png"></a>

                    <div class="media-body">
                        <div class="mar-btm">
                            <a href="#"
                                class="btn-link text-semibold media-heading box-inline">{{ $item->name }}</a>
                            <p class="text-muted text-sm"><i class="fa fa-mobile fa-lg"></i> -
                                From Mobile - 11 min ago</p>
                        </div>
                        <p>{{ $item->content }}</p>

                        <div class="pad-ver">
                            <span class="tag tag-sm">
								<i class="fa fa-heart text-danger"></i>
								<span>250 Likes</span>
                            </span>
                            <div class="btn-group">
                                <button class="btn btn-sm" onclick="like(this, {{$item->id}}, 'like')">
									<i class="fa fa-thumbs-up"></i>
								</button>
                                <button class="btn btn-sm" onclick="like(this, {{$item->id}}, 'dislike')">
									<i class="fa fa-thumbs-down"></i>
								</button>
                            </div>

							{!! view('frontend.templates.paper.component.commentReply', ["item" => $item]); !!}

                        </div>

                        @if (count($childrents = $item->getChildrent()))
                            {!! view('frontend.templates.paper.component.commentHistory', ['comments'=> $childrents])->render(); !!}
                        @endif
                        <span class="panel"></span>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endif

{{-- <div class="panel">
	<div class="panel-body">
		<div class="media-block pad-all">

			<a class="media-left" href="#"><img class="img-circle img-sm"
					alt="Profile Picture"
					src="https://bootdey.com/img/Content/avatar/avatar1.png"></a>

			<div class="media-body">
				<div class="mar-btm">
					<a href="#" class="btn-link text-semibold media-heading box-inline">John
						Doe</a>
					<p class="text-muted text-sm"><i class="fa fa-mobile fa-lg"></i> -
						From Mobile - 11 min ago</p>
				</div>
				<p>Lorem ipsum dolor sit amet.</p>

				<img class="img-responsive thumbnail" src="https://www.bootdey.com/image/400x300"
					alt="Image">

				<div class="pad-ver">
					<span class="tag tag-sm"><i class="fa fa-heart text-danger"></i>
						250 Likes</span>
					<div class="btn-group">
						<a class="btn btn-sm btn-default btn-hover-success" href="#"><i
								class="fa fa-thumbs-up"></i></a>
						<a class="btn btn-sm btn-default btn-hover-danger" href="#"><i
								class="fa fa-thumbs-down"></i></a>
					</div>
					<a class="btn btn-sm btn-default btn-hover-primary" href="#">Comment</a>
				</div>
				<hr>

				<div>
					<div class="media-block pad-all">
						<a class="media-left" href="#"><img class="img-circle img-sm"
								alt="Profile Picture"
								src="https://bootdey.com/img/Content/avatar/avatar2.png"></a>
						<div class="media-body">
							<div class="mar-btm">
								<a href="#"
									class="btn-link text-semibold media-heading box-inline">Maria
									Leanz</a>
								<p class="text-muted text-sm"><i class="fa fa-globe fa-lg"></i> -
									From Web - 2
									min ago</p>
							</div>
							<p>Duis autem vel eum iriure dolor in hendrerit in vulputate
								?</p>
							<div>
								<div class="btn-group">
									<a class="btn btn-sm btn-default btn-hover-success"
										href="#"><i class="fa fa-thumbs-up"></i></a>
									<a class="btn btn-sm btn-default btn-hover-danger"
										href="#"><i class="fa fa-thumbs-down"></i></a>
								</div>
								<a class="btn btn-sm btn-default btn-hover-primary"
									href="#">Comment</a>
							</div>
						</div>
					</div>
				</div>

				<div>
					<div class="media-block pad-all">
						<a class="media-left" href="#"><img class="img-circle img-sm"
								alt="Profile Picture"
								src="https://bootdey.com/img/Content/avatar/avatar2.png"></a>
						<div class="media-body">
							<div class="mar-btm">
								<a href="#"
									class="btn-link text-semibold media-heading box-inline">Maria
									Leanz</a>
								<p class="text-muted text-sm"><i class="fa fa-globe fa-lg"></i> -
									From Web - 2
									min ago</p>
							</div>
							<p>Duis autem vel eum iriure dolor in hendrerit in vulputate
								?</p>
							<div>
								<div class="btn-group">
									<a class="btn btn-sm btn-default btn-hover-success"
										href="#"><i class="fa fa-thumbs-up"></i></a>
									<a class="btn btn-sm btn-default btn-hover-danger"
										href="#"><i class="fa fa-thumbs-down"></i></a>
								</div>
								<a class="btn btn-sm btn-default btn-hover-primary"
									href="#">Comment</a>
							</div>
						</div>
					</div>
				</div>

				<div>
					<div class="media-block pad-all">
						<a class="media-left" href="#"><img class="img-circle img-sm"
								alt="Profile Picture"
								src="https://bootdey.com/img/Content/avatar/avatar2.png"></a>
						<div class="media-body">
							<div class="mar-btm">
								<a href="#"
									class="btn-link text-semibold media-heading box-inline">Maria
									Leanz</a>
								<p class="text-muted text-sm"><i class="fa fa-globe fa-lg"></i> -
									From Web - 2
									min ago</p>
							</div>
							<p>Duis autem vel eum iriure dolor in hendrerit in vulputate
								?</p>
							<div>
								<div class="btn-group">
									<a class="btn btn-sm btn-default btn-hover-success"
										href="#"><i class="fa fa-thumbs-up"></i></a>
									<a class="btn btn-sm btn-default btn-hover-danger"
										href="#"><i class="fa fa-thumbs-down"></i></a>
								</div>
								<a class="btn btn-sm btn-default btn-hover-primary"
									href="#">Comment</a>
							</div>

							<hr>

							<div>
								<div class="media-block pad-all">
									<a class="media-left" href="#"><img class="img-circle img-sm"
											alt="Profile Picture"
											src="https://bootdey.com/img/Content/avatar/avatar2.png"></a>
									<div class="media-body">
										<div class="mar-btm">
											<a href="#"
												class="btn-link text-semibold media-heading box-inline">Maria
												Leanz</a>
											<p class="text-muted text-sm"><i class="fa fa-globe fa-lg"></i> -
												From Web - 2
												min ago</p>
										</div>
										<p>Duis autem vel eum iriure dolor in hendrerit in vulputate
											?</p>
										<div>
											<div class="btn-group">
												<a class="btn btn-sm btn-default btn-hover-success"
													href="#"><i class="fa fa-thumbs-up"></i></a>
												<a class="btn btn-sm btn-default btn-hover-danger"
													href="#"><i class="fa fa-thumbs-down"></i></a>
											</div>
											<a class="btn btn-sm btn-default btn-hover-primary"
												href="#">Comment</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>

		</div>
	</div>
</div> --}}
