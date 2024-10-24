<div class="single-follow mb-45" id="most_detail_recent" style="background-color: #f4f4f4">
    <div class="single-box">
        @foreach ($listSocials as $item)
            <div class="follow-us d-flex align-items-center">
                <div class="follow-social">
                    <a href="{{ $item['url'] }}"><img src={{ asset($item['image']) }} alt=""></a>
                </div>
                <div class="follow-count">
                    <span>{{ $item['count'] }}</span>
                    <p>Fans</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
