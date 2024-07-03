@inject('DomHtml', 'App\Helper\HelperFunction')

<header>
    <style>
        @media only screen and (min-width: 720px) {
            li:hover > ul.chilMenu {
                top: 0% !important;
                visibility: visible;
                opacity: 1;
                display: inline-block;
                margin-left: 120px !important;
            }
        }

        .header-area .header-mid .header-banner img {
            width: revert-layer
        }
    </style>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header ">
            <div class="header-top black-bg d-none d-sm-block">
                <div class="container">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    <li class="title"><span class="flaticon-energy"></span> {{
                                        $DomHtml->getConfig('head_title') }}</li>
                                    <li>{{ $DomHtml->getConfig('head_conten') }}</li>
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul class="header-date">
                                    <li><span class="flaticon-calendar"></span> {{ $DomHtml->getConfig('contact_phone')
                                        }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-mid gray-bg">
                <div class="container">
                    <div class="row d-flex align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-3 col-lg-3 col-md-3 d-none d-md-block">
                            <div class="logo">
                                <a href="{{ route('/') }}">
                                    <img src={{ $DomHtml->getConfig('home_image') ?:
                                    asset('assets/frontend/img/logo/logo.png') }}
                                        width={{ $DomHtml->getConfig('home_image_width', 136)}}
                                        height={{ $DomHtml->getConfig('home_image_height', 55)}} />
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                            <div class="header-banner f-right ">
                                <img src={{ $DomHtml->getConfig('home_image_right',) ?:
                                asset('assets/frontend/img/gallery/header_card.png') }}
                                    width={{$DomHtml->getConfig('image_right_width', 750)}}
                                    height={{ $DomHtml->getConfig('image_right_height', 92)}} sty alt=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-8 col-lg-8 col-md-12 header-flex">
                            <!-- sticky -->
                            <div class="sticky-logo">
                                <a href="{{ route('/', ['id' => 1]) }}"><img src={{
                                        asset('assets/frontend/img/logo/logo.png') }} alt=""></a>
                            </div>
                            <!-- Main-menu  top category menu -->
                            @render(\App\ViewBlock\TopCategory::class)

                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <div class="header-right f-right d-none d-lg-block">
                                <!-- Heder social -->
                                <ul class="header-social">
                                    <li><a href="https://www.fb.com/sai4ull"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="{{ route('paper_cart') }}"><i
                                                class="fa fa-shopping-cart text-black"></i> {{$cart_count}}</a></li>
                                    @if (Auth::check())
                                        <li><a href="{{ route('user_logout') }}"><i class="fa fa-sign-out-alt"></i></a>
                                        </li>
                                    @else
                                        {{-- D:\xampp\htdocs\newpaper\public\assets\frontend\css\fontawesome-all.min.css
                                        --}}
                                        <li><a href="{{ route('user_login') }}"><i class="fa fa-user"></i></a></li>
                                    @endif

                                </ul>
                                <!-- Search Nav -->
                                <div class="nav-search search-switch">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-md-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>
