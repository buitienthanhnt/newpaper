@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form>
        <p>
            Choose some countries you'd like to visit:
            <select data-bind="options: availableCountries, selectedOptions: chosenCountries" size="5"
                multiple="true"></select>
        </p>
    </form>

    <script type="text/javascript">
        var viewModel = {
            availableCountries: ko.observableArray(['France', 'Germany', 'Spain']),
            chosenCountries: ko.observableArray(['Germany']) // Initially, only Germany is selected
        };

        // ... then later ...
        viewModel.chosenCountries.push('France'); // Now France is selected too
        ko.applyBindings(viewModel);
    </script>
@endsection

@section('content_page-ex1')
    <form>
        <p>
            Your country:
            <select name="country"
                data-bind="options: availableCountries,
							   optionsText: 'countryName',
							   value: selectedCountry,
							   optionsCaption: 'Choose...'"></select>
        </p>

        <div data-bind="visible: selectedCountry"> <!-- Appears when you select something -->
            You have chosen a country with population
            <span data-bind="text: selectedCountry() ? selectedCountry().countryPopulation : 'unknown'"></span>.
        </div>
    </form>

    <script type="text/javascript">
        var Country = function(name, population) {
            this.countryName = name;
            this.countryPopulation = population;
        };

        var viewModel = {
            availableCountries: ko.observableArray([
                new Country("UK", 65000000),
                new Country("USA", 320000000),
                new Country("Sweden", 29000000)
            ]),
            selectedCountry: ko.observable() // Nothing selected by default
        };
        ko.applyBindings(viewModel);
    </script>
@endsection
