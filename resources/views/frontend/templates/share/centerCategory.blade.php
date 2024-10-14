@inject('DomHtml', 'App\Helper\HelperFunction')
<div class="whats-news-wrapper">
    <!-- Heading & Nav Button -->
    <div class="row justify-content-between align-items-end mb-15">
        <div class="col-xl-4">
            <div class="section-tittle mb-30">
                <h3>Whats New</h3>
            </div>
        </div>
        <div class="col-xl-8 col-md-9">
            <div class="properties__button">
                <!--Nav Button  -->
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @if ($list_center)
                            <?php $i = 0; ?>
                            @foreach ($list_center as $center_item)
                                <a class="nav-item nav-link {{ $i == 0 ? 'active' : '' }}"
                                    id="{{ 'nav-' . $center_item->id . '-tab' }}" data-toggle="tab"
                                    href="{{ '#nav-' . $center_item->id }}" role="tab"
                                    aria-controls="{{ 'nav-' . $center_item->id }}"
                                    aria-selected="{{ $i == 0 ? 'true' : 'false' }}">{{ $center_item->name }}</a>
                                <?php $i += 1; ?>
                            @endforeach
                        @endif
                    </div>
                </nav>
                <!--End Nav Button  -->
            </div>
        </div>
    </div>
    <!-- Heading & Nav Button -->

    <!-- Tab content -->
    <div class="row">
        <div class="col-12">
            <!-- Nav Card -->
            <div class="tab-content" id="nav-tabContent">
                @if ($list_center)
                    <?php $center_one = 0; ?>
                    @foreach ($list_center as $center_conten)
                        <div class="tab-pane fade{{ !$center_one ? ' show active' : '' }}"
                            id="{{ 'nav-' . $center_conten->id }}" role="tabpanel"
                            aria-labelledby="{{ 'nav-' . $center_conten->id . '-tab' }}">
                            <div class="row">
                                @if ($papers = $center_conten->getPaperPaginate())
                                    <!-- Left Details Caption -->
                                    @if ($paper_first = $papers->first())
                                        <div class="col-xl-6">
                                            <div class="whats-news-single mb-40">
                                                <div class="whates-img">
                                                    <img src="{{ $paper_first->getImagePath() }}" alt="">
                                                </div>
                                                <div class="whates-caption">
                                                    <a href="#">
                                                        <h4
                                                            style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;">
                                                            {{ $paper_first->title }}
                                                        </h4>
                                                    </a>
                                                    <span>by
                                                        {{ $paper_first->writerName() }}
                                                        -
                                                        {{ date('M d, Y', strtotime($paper_first->updated_at)) }}
                                                        <a href="" class="text text-info" style="float: right;">
                                                            <i class="fa fa-eye"></i>
                                                            {{ $paper_first->viewCount() }}
                                                        </a>
                                                    </span>
                                                    <p
                                                        style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical;">
                                                        {{ $paper_first->short_conten }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- Right single caption -->
                                    <div class="col-xl-6 col-lg-12">
                                        <div class="row">
                                            <!-- single -->
                                            @if ($papers->first() && ($papers = $papers->diff([$papers->first()])))
                                                @foreach ($papers as $paper)
                                                    <div class="col-xl-12 col-lg-6 col-md-6 col-sm-10">
                                                        <div class="whats-right-single mb-20">
                                                            <div class="whats-right-img">
                                                                <img src="{{ $paper->getImagePath() }}"
                                                                    style="width: 124px; height: 104px;" alt="">
                                                            </div>
                                                            <div class="whats-right-cap">
                                                                <a title="{{ $paper->short_conten }}"
                                                                    href="{{ route('front_paper_detail', ['paper_id' => $paper->id, 'alias' => $paper->url_alias]) }}">
                                                                    <h4
                                                                        style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical;">
                                                                        {{ $paper->short_conten }}
                                                                    </h4>
                                                                </a>
                                                                <p>
                                                                    {{ date('M d, Y', strtotime($paper->updated_at)) }}
                                                                    <a href="" class="text text-info"
                                                                        style="float: right;">
                                                                        {{-- <i class="fa fa-eye"></i>  --}}
                                                                        {{ $paper->viewCount() }}
                                                                    </a>
                                                                </p>
                                                                <span class="colorb mb-10">{{ $paper->writerName() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <?php $center_one += 1; ?>
                    @endforeach
                @endif
            </div>
            <!-- End Nav Card -->
        </div>
    </div>
    <!-- Tab content -->
</div>
