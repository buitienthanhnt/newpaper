<div id="most-trending" style="background-color: #fff;"></div>
<script type="text/javascript">
    $(document).ready(function (){
        $.ajax({
            url: "{{ route('front_trending_html') }}",
            type: "GET",
            success: function(result) {
                if (result.dataHtml) {
                    $("#most-trending").append(result.dataHtml);
                }
            }
        })
    })
</script>
