@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
<p>
    count of array: 
    <span data-bind="text: anotherObservableArray().length"></span>
</p>
    <script type="text/javascript">
        // quan sat 1 mang dung: observableArray
        var anotherObservableArray = ko.observableArray([{
                name: "Bungle",
                type: "Bear"
            },
            {
                name: "George",
                type: "Hippo"
            },
            {
                name: "Zippy",
                type: "Unknown"
            }
        ]);

        // hàm applyBindings là bắt buộc, nhất định phải có. 
        ko.applyBindings(anotherObservableArray);
    </script>
@endsection
