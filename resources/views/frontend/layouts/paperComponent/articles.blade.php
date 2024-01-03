<div class="recent-articles pt-80 pb-80">
    <div class="container">
        <div class="recent-wrapper">

            <!-- Trending section Tittle -->
            <div class="row">
                @yield('articles_title')
            </div>
            <!-- section Tittle -->

            {{-- Trending articles_conten --}}
            <div class="row" id="most-trending">
                @yield('articles_conten')
            </div>
            {{-- articles_conten --}}

        </div>
    </div>
</div>