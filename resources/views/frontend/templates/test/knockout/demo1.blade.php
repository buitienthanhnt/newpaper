@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    {{-- <ul data-bind="foreach: products">
        <li class="product">
            <strong data-bind="text: name"></strong>
            <like-or-dislike params="value: userRating"></like-or-dislike>
        </li>
    </ul> --}}

    {{-- <button data-bind="click: addProduct">Add a product</button> --}}

    <script type="text/javascript">
        // ko.components.register('like-or-dislike', {
        //     viewModel: {
        //         require: 'model/component'
        //     },
        //     template: {
        //         require: 'text!templates/component.html'
        //     }
        // });
    </script>
@endsection
