<div class="trending-area fix pt-25 gray-bg">
    <div class="container">
        <div class="trending-main">
            <div class="row">
                <div class="col-lg-8">
                    @render(App\ViewBlock\TrendingLeft::class)
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        @render(App\ViewBlock\TrendingRight::class)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
