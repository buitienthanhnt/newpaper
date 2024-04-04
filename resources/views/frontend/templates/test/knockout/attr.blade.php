@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <a data-bind="attr: { href: url, title: details }, style: {color: color}">
        Report
    </a>

    <script type="text/javascript">
        var viewModel = {
            url: ko.observable("year-end.html"),
            details: ko.observable("Report including final year-end statistics"),
            color: 'blue'
        };
        // Causes the "profitPositive" class to be removed and "profitWarning" class to be added
        // viewModel.currentProfit(-50);
        ko.applyBindings(viewModel);
    </script>
@endsection
