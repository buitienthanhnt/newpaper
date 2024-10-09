<div class="most-recent-area" id="most_view_detail" data-page="1" style="background-color: #f4f4f4">
    <!-- Section Tittle -->
    <div class="small-tittle mb-20">
        <h4>Most Recent</h4>
    </div>
    <!-- Details -->
    {{-- <div class="most-recent mb-40">
        <div class="most-recent-img">
            <img src={{ asset('assets/frontend/img/gallery/most_recent.png') }} alt="">
            <div class="most-recent-cap">
                <span class="bgbeg">Vogue</span>
                <h4><a href="latest_news.html">What to Wear: 9+ Cute Work <br>
                        Outfits to Wear This.</a></h4>
                <p>Jhon | 2 hours ago</p>
            </div>
        </div>
    </div> --}}

    <!-- Single -->
    {{-- <div class="most-recent-single">
        <div class="most-recent-images">
            <img src={{ asset('assets/frontend/img/gallery/most_recent2.png') }} alt="">
        </div>
        <div class="most-recent-capt">
            <h4><a href="latest_news.html">Most Beautiful Things to Do in Sidney with Your BF</a>
            </h4>
            <p>Jhon | 3 hours ago</p>
        </div>
    </div> --}}
</div>

<script>
    var run = true;
    $(document).ready(function() {
        function loadMostView() {
            let conten_height = $(detail_main_conten).height();
            var data_page = $("#most_view_detail").attr('data-page');
            let url = "{{ route('paper_most_view') }}";
            let most_height = 172; // height of element
            // console.log(url + `/?page=${data_page}`);
            $.ajax({
                url: url + `?page=${data_page}`,
                type: 'GET',
                success: function(result) {
                    if (result) {
                        let most_recent = $(".most-recent");
                        result.forEach(element => {
                            if ($("#most_view_detail").height() + ($(
                                    most_detail_recent).height() + 30 * 2 + 45) +
                                most_height <=
                                conten_height) {
                                $(most_view_detail).append(`
                                    <div class="most-recent mb-40">
                                        <div class="most-recent-img">
                                            <img src="${element.image_path}" />
                                            <div class="most-recent-cap">
                                                <span class="bgbeg">Vogue</span>
                                                <h4><a href="${element.url}">${element.title}</a></h4>
                                                <p>Jhon | 2 hours ago</p>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            } else {
                                run = false;
                            }
                        });

                        $("#most_view_detail").attr('data-page', Number(data_page) + 1);
                        if ($("#most_view_detail").height() + ($(
                                most_detail_recent).height() + 30 * 2 + 45) + most_height <
                            conten_height) {
                            loadMostView(); // run again
                        }
                    }
                }.bind(this),
                error: function(e) {}
            })
        }

        loadMostView(); // run first
    })
</script>
