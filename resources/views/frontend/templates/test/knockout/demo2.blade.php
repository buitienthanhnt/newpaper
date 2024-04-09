@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_after_js')
@endsection

@section('content_page')
    <div id="platform">
        @if (PHP_OS === 'Linux')
            <p>day la {{ PHP_OS }} platform</p>
        @endif
        <p>noi dung trong demo2 </p>
    </div>

    <div>
        Today's message is: <span data-bind="text: myMessage"></span>
    </div>
@endsection

@section('js_after')
    <script type="text/javascript">
        // https://stackoverflow.com/questions/39079389/requirejs-with-knockout
        // https://www.c-sharpcorner.com/UploadFile/dbd951/how-to-use-requirejs-with-knockoutjs-in-Asp-Net-mvc4/

        requirejs(['app/messages', 'app/maink', 'app/demo2', 'knockout'], function(messages, maink, demo2, ko) {
            // console.log(messages.getHello());
            // console.log(maink.show());
            // console.log(demo2);
            ko.applyBindings({
                myMessage: '11111111'
            });
        });
    </script>
@endsection
