<section class="whats-news-area pt-50 pb-20 gray-bg">
    <div class="container">
        <div class="row">
            {{-- new_post left --}}
            <div class="col-lg-8">
                @render(App\ViewBlock\Frontend\CenterCategory::class)
                <div class="banner-one mt-20 mb-30">
                    <img src={{ $DomHtml->getConfig('post_banner_image') ?: asset('assets/frontend/img/gallery/body_card1.png') }}
                        width={{ $DomHtml->getConfig('home_image_width', 750) }}
                        height={{ $DomHtml->getConfig('home_image_height', 111) }} />
                </div>
            </div>
            {{-- new_post right --}}
            <div class="col-lg-4">
                @render(App\ViewBlock\Frontend\Social::class)
                @render(App\ViewBlock\Frontend\MostRecent::class)
            </div>
        </div>
    </div>
</section>
