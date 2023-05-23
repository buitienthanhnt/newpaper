<section class="whats-news-area pt-50 pb-20 gray-bg">
        <div class="container">
            <div class="row">

                {{-- new_post left --}}
                <div class="col-lg-8">

                    @yield('new_post_left')

                    <!-- Banner -->
                    @yield('new_post_banner')
                    <!-- Banner -->

                </div>
                {{-- new_post left --}}

                {{-- new_post right --}}
                <div class="col-lg-4">

                    <!-- Flow Socail -->
                    @yield('flow_socail')
                    <!-- Flow Socail -->

                    <!-- Most Recent Area -->
                    @yield('most_recent')
                    <!-- Most Recent Area -->
                </div>
                {{-- new_post right --}}

            </div>
        </div>
    </section>