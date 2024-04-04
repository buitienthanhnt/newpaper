@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <ul data-bind="foreach: planets">
        <li>
            Planet: <b data-bind="text: name"> </b>
            <div data-bind="if: capital, style: {color: 'red'}">
                Capital: <b data-bind="text: capital.cityName"> </b>
            </div>
        </li>
    </ul>

    <script>
        ko.applyBindings({
            planets: [{
                    name: 'Mercury',
                    capital: null
                },
                {
                    name: 'Earth',
                    capital: {
                        cityName: 'Barnsley'
                    }
                }
            ]
        });
    </script>
@endsection


@section('content_page-ex1')
    <label><input type="checkbox" data-bind="checked: displayMessage" /> Display message</label>
    <div data-bind="if: displayMessage">Here is a message. Astonishing.</div>

    <script type="text/javascript">
        ko.applyBindings({
            displayMessage: ko.observable(false)
        });
    </script>
@endsection
