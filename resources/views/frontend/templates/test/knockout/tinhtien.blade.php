@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_before_js')
    <script type="text/javascript">
        var baseUrl = "<?=route('/')?>"
        var flashToken = "<?=csrf_token()?>"
    </script>
@endsection

@section('head_after_js')
@endsection

@section('content_page')
    <div id="demo-tinhtien"></div>

    <script type="text/javascript">
        requirejs(['components/Tinhtien', 'viewModal/BuildUrl'],
            function(Component5, buildUrl) {
                Component5({
                    initData: {},
                    element: $("#demo-tinhtien")[0]
                })
            });
    </script>
@endsection

@section('js_after')
@endsection
