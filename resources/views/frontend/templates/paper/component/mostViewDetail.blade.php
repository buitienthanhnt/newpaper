<div class="most-recent-area" id="most-view-in-detail" data-page="1">
    <!-- Section Tittle -->
    <div class="small-tittle mb-20">
        <h4>Most Recent</h4>
    </div>
    <!-- Details -->
    <div class="most-recent mb-40">
        <div class="most-recent-img">
            <img src={{ asset('assets/frontend/img/gallery/most_recent.png') }} alt="">
            <div class="most-recent-cap">
                <span class="bgbeg">Vogue</span>
                <h4><a href="latest_news.html">What to Wear: 9+ Cute Work <br>
                        Outfits to Wear This.</a></h4>
                <p>Jhon | 2 hours ago</p>
            </div>
        </div>
    </div>
    <!-- Single -->
    <div class="most-recent-single">
        <div class="most-recent-images">
            <img src={{ asset('assets/frontend/img/gallery/most_recent1.png') }} alt="">
        </div>
        <div class="most-recent-capt">
            <h4><a href="latest_news.html">Scarlett’s disappointment at latest accolade</a></h4>
            <p>Jhon | 2 hours ago</p>
        </div>
    </div>
    <!-- Single -->
    <div class="most-recent-single">
        <div class="most-recent-images">
            <img src={{ asset('assets/frontend/img/gallery/most_recent2.png') }} alt="">
        </div>
        <div class="most-recent-capt">
            <h4><a href="latest_news.html">Most Beautiful Things to Do in Sidney with Your BF</a>
            </h4>
            <p>Jhon | 3 hours ago</p>
        </div>
    </div>

    <div>
        <center>
            <button onclick="loadMostData()" class="btn">load data</button>
        </center>
    </div>

    <script>
        function loadMostData() {
            var current_page = 1;
            var data_page = $("#most-view-in-detail").attr('data-page');
            let url = "{{ route('mostviewdetail') }}";
            console.log(url + `/?page=${data_page}`);
            $.ajax({
                url: url + `/?page=${data_page}`,
                type: 'GET',
                success: function(result) {
                    if (result) {
                        var data = JSON.parse(result);
                        if (data.code == 200) {
                            current_page += 1;

                        }
                    }
                }.bind(this),
                error: function(e) {}
            })
        }
    </script>
</div>
