<!-- Search model Begin -->

<div class="search-model-box">
    <div class="d-flex align-items-center h-100 justify-content-center">
        <div class="search-close-btn">+</div>
        <form id="form_search_pager" class="search-model-form">
            <input type="text" id="search-input" required placeholder="Searching key.....">
        </form>
		<p class="btn btn-dark" id="search_action">search</p>
    </div>
</div>

<script>
	$(document).ready(function(){

		$("#search_action").click(function(){
			$("#form_search_pager").submit();
		});
	})
</script>
<!-- Search model end -->
