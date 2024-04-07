@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <div>
        You've clicked <span data-bind="text: numberOfClicks"></span> times
        <button data-bind="click: incrementClickCounter">Click me</button>
    </div>

    <script type="text/javascript">
        var viewModel = {
            numberOfClicks: ko.observable(0),
            incrementClickCounter: function() {
                var previousCount = this.numberOfClicks();
                this.numberOfClicks(previousCount + 1);
            }
        };
       

        ko.applyBindings(viewModel);
    </script>
@endsection
