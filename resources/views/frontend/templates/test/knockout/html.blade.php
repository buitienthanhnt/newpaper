@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <div>
        <div data-bind="html: details"></div>
    </div>

    <script type="text/javascript">
        var viewModel = {
            details: ko.observable() // Initially blank
        };
        viewModel.details(
            "<em>For further details, view the report <a href='report.html'>here</a>.</em>"); // HTML content appears

        ko.applyBindings(viewModel);
    </script>
@endsection
