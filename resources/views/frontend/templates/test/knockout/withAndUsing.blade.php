@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form data-bind="submit: getTweets">
        Twitter account:
        <input data-bind="value: twitterName" />
        <button type="submit">Get tweets</button>
    </form>

    <div data-bind="with: resultData">
        <h3>Recent tweets fetched at <span data-bind="text: retrievalDate"> </span></h3>
        <ol data-bind="foreach: topTweets">
            <li data-bind="text: text"></li>
        </ol>

        <button data-bind="click: $parent.clearResults">Clear tweets</button>
    </div>

    <script type="text/javascript">
		// Lưu ý 1: Sử dụng "với" hoặc "sử dụng" mà không có phần tử container
		
        function AppViewModel() {
            var self = this;
            self.twitterName = ko.observable('@example');
            self.resultData = ko.observable(); // No initial value

            self.getTweets = function() {
                var name = self.twitterName(),
                    simulatedResults = [{
                            text: name + ' What a nice day.'
                        },
                        {
                            text: name + ' Building some cool apps.'
                        },
                        {
                            text: name + ' Just saw a famous celebrity eating lard. Yum.'
                        }
                    ];

                self.resultData({
                    retrievalDate: new Date(),
                    topTweets: simulatedResults
                });
            }

            self.clearResults = function() {
                self.resultData(undefined);
            }
        }

        ko.applyBindings(new AppViewModel());
    </script>
@endsection

@section('content_page-ex1')
    <h1 data-bind="text: city"> </h1>
    <p data-bind="using: coords">
        Latitude: <span data-bind="text: latitude"> </span>,
        Longitude: <span data-bind="text: longitude"> </span>
    </p>

    <script type="text/javascript">
        ko.applyBindings({
            city: "London",
            coords: {
                latitude: 51.5001524,
                longitude: -0.1262362
            }
        });
    </script>
@endsection
