@inject('DomHtml', 'App\Helper\HelperFunction')

<footer>
    <!-- Footer Start-->
    <div class="footer-main footer-bg">
        <div class="footer-area footer-padding">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <div class="single-footer-caption mb-30">
                                <!-- logo -->
                                <div class="footer-logo">
                                    <img src={{ $DomHtml->getConfig('home_image') ?:
                                    asset('assets/frontend/img/logo/logo.png') }}
                                    width={{ $DomHtml->getConfig('home_image_width', 136)}}
                                    height={{ $DomHtml->getConfig('home_image_height', 55)}} />
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p class="info1">{{ $DomHtml->getConfig('foot_content') }}</p>
                                        <p class="info2">{{ $DomHtml->getConfig('contact_address') }}</p>
                                        <p class="info2">Phone: <a href="tel:{{ $DomHtml->getConfig('contact_phone')}}">{{ $DomHtml->getConfig('contact_phone')}}</a> <br> Cell: {{
                                            $DomHtml->getConfig('contact_phone') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Popular post</h4>
                            </div>
                            <!-- Popular post -->
                            <div class="whats-right-single mb-20">
                                <div class="whats-right-img">
                                    <img src={{ asset('assets/frontend/img/gallery/footer_post1.png') }} alt="">
                                </div>
                                <div class="whats-right-cap">
                                    <h4><a href="latest_news.html">Scarlett’s disappointment at latest accolade</a>
                                    </h4>
                                    <p>Jhon | 2 hours ago</p>
                                </div>
                            </div>
                            <!-- Popular post -->
                            <div class="whats-right-single mb-20">
                                <div class="whats-right-img">
                                    <img src={{ asset('assets/frontend/img/gallery/footer_post2.png') }} alt="">
                                </div>
                                <div class="whats-right-cap">
                                    <h4><a href="latest_news.html">Scarlett’s disappointment at latest accolade</a>
                                    </h4>
                                    <p>Jhon | 2 hours ago</p>
                                </div>
                            </div>
                            <!-- Popular post -->
                            <div class="whats-right-single mb-20">
                                <div class="whats-right-img">
                                    <img src={{ asset('assets/frontend/img/gallery/footer_post3.png') }} alt="">
                                </div>
                                <div class="whats-right-cap">
                                    <h4><a href="latest_news.html">Scarlett’s disappointment at latest accolade</a>
                                    </h4>
                                    <p>Jhon | 2 hours ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-5 col-sm-7">
                        <div class="single-footer-caption mb-50">
                            <div class="banner">
                                {{-- <img src={{ asset('assets/frontend/img/gallery/body_card4.png') }} alt=""> --}}
                                <img src={{ $DomHtml->getConfig('footer_image') ?:
                                asset('assets/frontend/img/gallery/body_card4.png') }}
                                width={{ $DomHtml->getConfig('home_image_width', 264)}}
                                height={{ $DomHtml->getConfig('home_image_height', 333)}} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer-bottom aera -->
        <div class="footer-bottom-area footer-bg">
            <div class="container">
                <div class="footer-border">
                    <div class="row d-flex align-items-center">
                        <div class="col-xl-12 ">
                            <div class="footer-copy-right text-center">
                                <p>
                                    {!! $DomHtml->getConfig('coppyright') !!}
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                    {{-- Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script> All rights reserved | This template is made with <i class="fa fa-heart"
                                        aria-hidden="true"></i> by <a href="https://colorlib.com"
                                        target="_blank">Colorlib</a> --}}
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>