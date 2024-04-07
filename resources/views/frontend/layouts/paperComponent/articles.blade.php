<div class="recent-articles pt-20 pb-20 gray-bg">
    <div class="container">
        <div class="recent-wrapper">

            <!-- Trending section Tittle -->
            <div class="row">
                @yield('articles_title')
            </div>
            <!-- section Tittle -->

            {{-- Trending articles_conten --}}
            <div class="col-md-12 pt-20" id="most-trending" style="background-color: #fff;">
                @yield('articles_conten')
            </div>
            {{-- articles_conten --}}

        </div>
    </div>
</div>