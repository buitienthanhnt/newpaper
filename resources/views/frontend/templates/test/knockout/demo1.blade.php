@extends('frontend.templates.test.knockout.baseTemplate')

@section('js_after')
    <script type="text/javascript">
        requirejs(['app/maink'], function(maink) {
            console.log(maink.show());;
        });
    </script>
@endsection

@section('head_after_js')
@endsection

@section('content_page')
    <script type="text/javascript">
        define('ex1demo', function(require) {
            console.log('ex1 demo');
            return {
                a: 123,
                name: 'tha nan'
            }
        });

        define('ex2demo', function(require) {
            var self = this;
           this.name = 'tha demo';
           this.age = 12;
        });

        requirejs(['ex1demo', 'ex2demo'], function(ex1demo, ex2demo){
            let a1 = ex1demo;
            let a2 = ex2demo;
            console.log(a1, ex2demo);
        })
    </script>
@endsection
