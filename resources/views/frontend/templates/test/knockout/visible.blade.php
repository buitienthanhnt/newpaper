@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <div data-bind="visible: shouldShowMessage">
        You will see this message only when "shouldShowMessage" holds a true value.
    </div>

    <script type="text/javascript">
        var viewModel = {
            shouldShowMessage: ko.observable(true) // Message initially visible
        };
        // viewModel.shouldShowMessage(false); // ... now it's hidden
        // viewModel.shouldShowMessage(true); // ... now it's visible again
        ko.applyBindings(viewModel);
    </script>
@endsection
