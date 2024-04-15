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
    <div id="demo-comp5"></div>

    <script type="text/javascript">
        requirejs(['components/Component5', 'viewModal/BuildUrl'],
            function(Component5, buildUrl) {
                console.log(buildUrl.getApiPath('info', {a: 123, b: 'tha api'}), buildUrl.token);

                Component5({
                    initData: {
                        people: []
                    },
                    element: $("#demo-comp5")[0]
                })
            });
    </script>
@endsection

@section('js_after')
@endsection
