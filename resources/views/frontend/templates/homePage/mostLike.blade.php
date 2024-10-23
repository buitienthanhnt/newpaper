<div id="likeMost" style="background-color: #fff;"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('front_most_like_html') }}",
            type: "GET",
            success: function(result) {
                if (result.dataHtml) {
                    $("#likeMost").append(result.dataHtml);
                }
            }
        })
    })
</script>
