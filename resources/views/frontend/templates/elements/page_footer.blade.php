<!-- Search model Begin -->

<div class="search-model-box">
    <div class="d-flex align-items-center h-100 justify-content-center">
        <div class="search-close-btn">+</div>
        <form id="form_search_pager" class="search-model-form" action="{{ route('search_all') }}">
            <input type="text" name="search" id="search-input" required placeholder="Searching key....." required>
            <button class="btn btn-dark" id="search_action" type="submit">search</button>
        </form>
		
    </div>
</div>

<script>
	$(document).ready(function(){

		// $("#search_action").click(function(){

		// 	$("#form_search_pager").submit();
		// });
	})
</script>
<!-- Search model end -->
