@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <div data-bind="class: profitStatus">
        Profit Information
    </div>

    <div data-bind="css: { profitWarning: currentProfit() < 0, majorHighlight: isSevere }">
        Profit Information
     </div>

    <script type="text/javascript">
        var viewModel = {
            currentProfit: ko.observable(150000)
        };

        // Evalutes to a positive value, so initially we apply the "profitPositive" class
        viewModel.profitStatus = ko.pureComputed(function() {
            return this.currentProfit() < 0 ? "profitWarning" : "profitPositive";
        }, viewModel);

        // Causes the "profitPositive" class to be removed and "profitWarning" class to be added
        // viewModel.currentProfit(-50);
        ko.applyBindings(viewModel);
    </script>
@endsection
