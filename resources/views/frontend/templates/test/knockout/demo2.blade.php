@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_after_js')
@endsection

@section('content_page')
<div id="platform">
    @if (PHP_OS === 'Linux')
        <p>day la {{PHP_OS}} platform</p>
    @endif
	<p>noi dung trong demo2 </p>
</div>
@endsection

@section('js_after')
    <script type="text/javascript">
        requirejs(['app/messages', 'app/maink', 'app/demo2'], function(messages, maink, demo2) {
            console.log(messages.getHello());
			console.log(maink.show());
            console.log(demo2);
        });
    </script>
@endsection
