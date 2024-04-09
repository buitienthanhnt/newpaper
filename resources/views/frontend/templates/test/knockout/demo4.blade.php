@extends('frontend.templates.test.knockout.baseTemplate')

@section('head_after_js')
@endsection

@section('content_page')
    <div id="demo-comp1">
        <p data-bind="text: name"></p>
    </div>

    <div id="demo-comp2">
        <p>
            age by knock <!--ko text: age--><!--/ko-->
        </p>
        <span>fullName: <!--ko text: fullName--><!--/ko--></span>
        <div style="display: grid; grid-gap: 20px">
            <div>
                <span>name: </span>
                <input type="text" data-bind="value: name">
            </div>
            <div>
                <span>age: </span>
                <input type="text" data-bind="value: age">
            </div>
        </div>
    </div>

    <div id="demo-comp3">
        address: <p data-bind="text: address"></p>
        name: <p data-bind="text: name"></p>
    </div>

    <script type="text/javascript">
        // use multi applyBindings and model in one page 
        // function viewmodel1() {
        //     var self = this;
        //     self.name = 'viewmodel1'
        // }

        // function viewmodel2() {
        //     var self = this;
        //     self.age = 12
        // }

        // function viewmodel3() {
        //     var self = this;
        //     self.address = 'nam tan- truc noi -truc ninh- nam dinh'
        // }

        // ko.applyBindings(model, element);
        // ko.applyBindings(viewmodel1, $("#demo-comp1")[0]);
        // ko.applyBindings(viewmodel2, $("#demo-comp2")[0]);
        // ko.applyBindings(viewmodel3, $("#demo-comp3")[0]);

        requirejs(['knockout', 'viewModal/component-like-widget', 'app/Component', 'viewModal/demo1'],
            function(ko, likeW, Component) {

                // ko.applyBindings(new likeW({name: 'tha nan'}), $("#demo-comp3")[0]);

                Component({
                    viewModel: 'viewModal/demo1',
                    initData: {
                        age: 23,
                        name: 'test@gmail'
                    },
                    element: $("#demo-comp2")[0]
                })
            });
    </script>
@endsection

@section('js_after')
@endsection
