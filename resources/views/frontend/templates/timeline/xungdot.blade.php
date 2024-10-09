@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
    <script src="{{ asset('assets/frontend/js/commentReply.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/commentHistory.js') }}"></script>
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection


@section('title')
    detail page
@endsection

@section('css_after')
    <link rel="stylesheet" href={{ asset('assets/frontend/css/paper/detail.css') }}>
    <style type="text/css">
        body {
            margin-top: 20px;
        }

        .timeline {
            border-left: 3px solid #727cf5;
            border-bottom-right-radius: 4px;
            border-top-right-radius: 4px;
            background: rgba(114, 124, 245, 0.09);
            margin: 0 auto;
            letter-spacing: 0.2px;
            position: relative;
            line-height: 1.4em;
            font-size: 1.03em;
            padding: 50px;
            list-style: none;
            text-align: left;
            max-width: 40%;
        }

        @media (max-width: 767px) {
            .timeline {
                max-width: 98%;
                padding: 25px;
            }
        }

        .timeline h1 {
            font-weight: 300;
            font-size: 1.4em;
        }

        .timeline h2,
        .timeline h3 {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .timeline .event {
            border-bottom: 1px dashed #e8ebf1;
            padding-bottom: 25px;
            margin-bottom: 25px;
            position: relative;
        }

        @media (max-width: 767px) {
            .timeline .event {
                padding-top: 30px;
            }
        }

        .timeline .event:last-of-type {
            padding-bottom: 0;
            margin-bottom: 0;
            border: none;
        }

        .timeline .event:before,
        .timeline .event:after {
            position: absolute;
            display: block;
            top: 0;
        }

        .timeline .event:before {
            left: -207px;
            content: attr(data-date);
            text-align: right;
            font-weight: 100;
            font-size: 0.9em;
            min-width: 120px;
        }

        @media (max-width: 767px) {
            .timeline .event:before {
                left: 0px;
                text-align: left;
            }
        }

        .timeline .event:after {
            -webkit-box-shadow: 0 0 0 3px #727cf5;
            box-shadow: 0 0 0 3px #727cf5;
            left: -55.8px;
            background: #fff;
            border-radius: 50%;
            height: 9px;
            width: 9px;
            content: "";
            top: 5px;
        }

        @media (max-width: 767px) {
            .timeline .event:after {
                left: -31.8px;
            }
        }

        .rtl .timeline {
            border-left: 0;
            text-align: right;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
            border-right: 3px solid #727cf5;
        }

        .rtl .timeline .event::before {
            left: 0;
            right: -170px;
        }

        .rtl .timeline .event::after {
            left: 0;
            right: -55.8px;
        }
    </style>

    <style type="text/css">
        body {
            margin-top: 20px;
            background: #eee;
        }

        .timeline-centered {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-centered.timeline-sm .timeline-entry {
            margin-bottom: 20px !important;
        }

        .timeline-centered.timeline-sm .timeline-entry .timeline-entry-inner .timeline-label {
            padding: 1em;
        }

        .timeline-centered:before,
        .timeline-centered:after {
            content: " ";
            display: table;
        }

        .timeline-centered:after {
            clear: both;
        }

        .timeline-centered:before {
            content: '';
            position: absolute;
            display: block;
            width: 7px;
            background: #ffffff;
            left: 50%;
            top: 20px;
            bottom: 20px;
            margin-left: -4px;
        }

        .timeline-centered .timeline-entry {
            position: relative;
            width: 50%;
            float: right;
            margin-bottom: 70px;
            clear: both;
        }

        .timeline-centered .timeline-entry:before,
        .timeline-centered .timeline-entry:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry:after {
            clear: both;
        }

        .timeline-centered .timeline-entry.begin {
            margin-bottom: 0;
        }

        .timeline-centered .timeline-entry.left-aligned {
            float: left;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
            margin-left: 0;
            margin-right: -28px;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
            left: auto;
            right: -115px;
            text-align: left;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
            float: right;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
            margin-left: 0;
            margin-right: 85px;
        }

        .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
            left: auto;
            right: 0;
            margin-left: 0;
            margin-right: -9px;
            -moz-transform: rotate(180deg);
            -o-transform: rotate(180deg);
            -webkit-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .timeline-centered .timeline-entry .timeline-entry-inner {
            position: relative;
            margin-left: -31px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:before,
        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner:after {
            clear: both;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
            position: absolute;
            left: -115px;
            text-align: right;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span {
            display: block;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span:first-child {
            font-size: 18px;
            font-weight: bold;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time>span:last-child {
            font-size: 12px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
            background: #fff;
            color: #999999;
            display: block;
            width: 60px;
            height: 60px;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding-box;
            background-clip: padding-box;
            border-radius: 50%;
            text-align: center;
            border: 7px solid #ffffff;
            line-height: 45px;
            font-size: 15px;
            float: left;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-primary {
            background-color: #dc6767;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-success {
            background-color: #5cb85c;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-info {
            background-color: #5bc0de;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-warning {
            background-color: #f0ad4e;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-danger {
            background-color: #d9534f;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-red {
            background-color: #bf4346;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-green {
            background-color: #488c6c;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-blue {
            background-color: #0a819c;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-yellow {
            background-color: #f2994b;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-orange {
            background-color: #e9662c;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-pink {
            background-color: #bf3773;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-violet {
            background-color: #9351ad;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-grey {
            background-color: #4b5d67;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-dark {
            background-color: #594857;
            color: #fff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
            position: relative;
            background: #ffffff;
            padding: 1.7em;
            margin-left: 85px;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-red {
            background: #bf4346;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-red:after {
            border-color: transparent #bf4346 transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-red .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-red p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-green {
            background: #488c6c;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-green:after {
            border-color: transparent #488c6c transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-green .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-green p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-orange {
            background: #e9662c;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-orange:after {
            border-color: transparent #e9662c transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-orange .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-orange p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-yellow {
            background: #f2994b;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-yellow:after {
            border-color: transparent #f2994b transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-yellow .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-yellow p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-blue {
            background: #0a819c;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-blue:after {
            border-color: transparent #0a819c transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-blue .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-blue p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-pink {
            background: #bf3773;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-pink:after {
            border-color: transparent #bf3773 transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-pink .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-pink p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-violet {
            background: #9351ad;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-violet:after {
            border-color: transparent #9351ad transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-violet .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-violet p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-grey {
            background: #4b5d67;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-grey:after {
            border-color: transparent #4b5d67 transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-grey .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-grey p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-dark {
            background: #594857;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-dark:after {
            border-color: transparent #594857 transparent transparent;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-dark .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label.bg-dark p {
            color: #ffffff;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
            content: '';
            display: block;
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 9px 9px 9px 0;
            border-color: transparent #ffffff transparent transparent;
            left: 0;
            top: 20px;
            margin-left: -9px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label .timeline-title,
        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
            color: #999999;
            margin: 0;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p+p {
            margin-top: 15px;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label .timeline-title {
            margin-bottom: 10px;
            font-family: 'Oswald';
            font-weight: bold;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label .timeline-title span {
            -webkit-opacity: .6;
            -moz-opacity: .6;
            opacity: .6;
            -ms-filter: alpha(opacity=60);
            filter: alpha(opacity=60);
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p .timeline-img {
            margin: 5px 10px 0 0;
        }

        .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p .timeline-img.pull-right {
            margin: 5px 0 0 10px;
        }
    </style>
@endsection


@section('main_conten')
    <!-- About US Start -->
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />


                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Timeline</h6>
                                    <div id="content">
                                        <ul class="timeline">
                                            <li class="event" data-date="12:30 - 1:00pm">
                                                <h3>Registration</h3>
                                                <p>Get here on time, it's first come first serve. Be late, get turned away.
                                                </p>
                                            </li>
                                            <li class="event" data-date="2:30 - 4:00pm">
                                                <h3>Opening Ceremony</h3>
                                                <p>Get ready for an exciting event, this will kick off in amazing fashion
                                                    with MOP
                                                    &amp; Busta Rhymes as an opening show.</p>
                                            </li>
                                            <li class="event" data-date="5:00 - 8:00pm">
                                                <h3>Main Event</h3>
                                                <p>This is where it all goes down. You will compete head to head with your
                                                    friends
                                                    and rivals. Get ready!</p>
                                            </li>
                                            <li class="event" data-date="8:30 - 9:30pm">
                                                <h3>Closing Ceremony</h3>
                                                <p>See how is the victor and who are the losers. The big stage is where the
                                                    winners
                                                    bask in their own glory.</p>
                                            </li>
                                            <li class="event" data-date="5:00 - 8:00pm">
                                                <h3>Main Event</h3>
                                                <p>This is where it all goes down. You will compete head to head with your
                                                    friends
                                                    and rivals. Get ready!</p>
                                            </li>
                                            <li class="event" data-date="8:30 - 9:30pm">
                                                <h3>Closing Ceremony</h3>
                                                <p>See how is the victor and who are the losers. The big stage is where the
                                                    winners
                                                    bask in their own glory.</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container bootstrap snippets bootdeys">
                            <div class="col-md-9">
                                <div class="timeline-centered timeline-sm">
                                    <article class="timeline-entry">
                                        <div class="timeline-entry-inner">
                                            <time datetime="2014-01-10T03:45" class="timeline-time"><span>12:45
                                                    AM</span><span>Today</span></time>
                                            <div class="timeline-icon bg-violet"><i class="fa fa-exclamation"></i></div>
                                            <div class="timeline-label">
                                                <h4 class="timeline-title">New Project</h4>

                                                <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add
                                                    and offered
                                                    prepare how cordial.</p>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="timeline-entry left-aligned">
                                        <div class="timeline-entry-inner">
                                            <time datetime="2014-01-10T03:45" class="timeline-time"><span>9:15
                                                    AM</span><span>Today</span></time>
                                            <div class="timeline-icon bg-green"><i class="fa fa-group"></i></div>
                                            <div class="timeline-label bg-green">
                                                <h4 class="timeline-title">Job Meeting</h4>

                                                <p>Caulie dandelion maize lentil collard greens radish arugula sweet pepper
                                                    water spinach
                                                    kombu courgette.</p>
                                            </div>
                                        </div>
                                    </article>
                                    <article class="timeline-entry">
                                        <div class="timeline-entry-inner">
                                            <time datetime="2014-01-09T13:22" class="timeline-time"><span>8:20
                                                    PM</span><span>04/03/2013</span></time>
                                            <div class="timeline-icon bg-orange"><i class="fa fa-paper-plane"></i></div>
                                            <div class="timeline-label bg-orange">
                                                <h4 class="timeline-title">Daily Feeds</h4>

                                                <p><img src="https://www.bootdey.com/image/45x45/" alt=""
                                                        class="timeline-img pull-left">Parsley amaranth tigernut silver beet
                                                    maize fennel
                                                    spinach ricebean black-eyed. Tolerably earnestly middleton extremely
                                                    distrusts she boy
                                                    now not. Add and offered prepare how cordial.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-entry-inner">
                                            <div style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);"
                                                class="timeline-icon"><i class="fa fa-plus"></i></div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About US End -->
@endsection

@section('morning_post')
    {{-- <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Nav Card -->
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane active" id="{{ 'nav-' . 'after-detail' }}" role="tabpanel"
                            aria-labelledby="{{ 'nav-' . 'after-detail' . '-tab' }}">
                            <div class="row">
                                <!-- Left Details Caption -->
                                @if ($top_paper)
                                    @foreach ($top_paper as $paper_first)
                                        <div class="col-xl-6">
                                            <div class="whats-news-single mb-20">
                                                <div class="whates-img">
                                                    <img src="{{ $paper_first->image_path }}" alt="">
                                                </div>
                                                <div class="whates-caption">
                                                    <h4><a
                                                            href="{{ route('front_paper_detail', ['alias' => $paper_first->url_alias, 'page' => $paper_first->id]) }}">{{ $paper_first->title }}</a>
                                                    </h4>
                                                    <p>{{ $paper_first->short_conten }}</p>
                                                    <span>by
                                                        {{ $paper_first->to_writer()->getResults() ? $paper_first->to_writer()->getResults()->name : '' }}
                                                        -
                                                        {{ date('M d, Y', strtotime($paper_first->updated_at)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <!-- Right single caption -->
                                <div class="col-xl-12 col-lg-12">
                                    <div class="whats-news-single mb-20" id="whats-right-single">
                                        <!-- single -->
                                        @if ($top_paper && $papers)
                                            @foreach ($papers as $paper)
                                                <div class="row">
                                                    <div class="whats-right-single mb-10">
                                                        <div class="col-md-6">
                                                            <img src="{{ $paper->image_path }}" class="whates-img"
                                                                style="width: 100%; height: auto;" alt="">
                                                        </div>
                                                        <div class="col-md-6 whats-right-cap">
                                                            <h4>
                                                                <a
                                                                    href="{{ route('front_paper_detail', ['alias' => $paper->url_alias, 'page' => $paper->id]) }}">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <center>
                                <button id="load_more" data-page="1" class="btn btn-info" onclick="load_more()">show
                                    more</button>
                            </center>

                        </div>
                    </div>
                    <!-- End Nav Card -->
                </div>
            </div>
        </div>
    </div> --}}
@endsection
