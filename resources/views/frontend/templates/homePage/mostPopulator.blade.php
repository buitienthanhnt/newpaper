<div id="most-populator"></div>
<script type="text/javascript">
    $(document).ready(function (){
        $.ajax({
            url: "{{ route('front_most_populator_html') }}",
            type: "GET",
            success: function(result) {
                if (result.dataHtml) {
                    let mostPopur = $("#most-populator")
                    mostPopur.append(result.dataHtml);
                }
            }
        })
    })
</script>
