@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <div data-bind="style: { color: currentProfit() < 0 ? 'red' : 'black', width: 100 }, text: '123123'">
        Profit Information
    </div>

    <script type="text/javascript">
        var viewModel = {
            currentProfit: ko.observable(150000) // Positive value, so initially black
        };

        viewModel.currentProfit(-50); // Causes the DIV's contents to go red
        ko.applyBindings(viewModel);
    </script>
@endsection
