@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <ul data-bind="foreach: products">
        <li class="product">
            <strong data-bind="text: name"></strong>
            <like-widget params="value: userRating"></like-widget>
        </li>
    </ul>


    <script type="text/javascript">
    // https://knockoutjs.com/documentation/component-binding.html
        ko.components.register('like-widget', {
            viewModel: function(params) {
                this.chosenValue = params.value

                // Behaviors
                this.like = function() {
                    this.chosenValue('like');
                }.bind(this);

                this.dislike = function() {
                    this.chosenValue('dislike');
                }.bind(this);
            },
            template: '<div class="like-or-dislike" data-bind="visible: !chosenValue()">\
                    <button data-bind="click: like">Like it</button>\
                    <button data-bind="click: dislike">Dislike it</button>\
                </div>\
                <div class="result" data-bind="visible: chosenValue">\
                    You <strong data-bind="text: chosenValue"></strong> it\
                </div>'
        });


        function Product(name, rating) {
            this.name = name;
            this.userRating = ko.observable(rating || null);
        }

        function MyViewModel() {
            this.products = [
                new Product('Garlic bread'),
                new Product('Pain au chocolat'),
                new Product('Seagull spaghetti', 'like') // This one was already 'liked'
            ];
        }

        ko.applyBindings(new MyViewModel());
    </script>
@endsection
