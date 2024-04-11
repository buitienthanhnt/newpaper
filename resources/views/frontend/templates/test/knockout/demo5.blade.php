@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_after_js')
@endsection

@section('content_page')
    <div id="demo-comp5"></div>

    <div id="demo-comp4"></div>

    <script type="text/javascript">
        requirejs(['components/Component5'],
            function(Component5) {
                Component5({
                    initData: {
                        age: null,
                        name: null,
                        people: []

                    },
                    element: $("#demo-comp5")[0]
                })
            });
    </script>
@endsection

@section('js_after')
@endsection
