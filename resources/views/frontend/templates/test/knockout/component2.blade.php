@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <h4>First instance, without parameters</h4>
    <div data-bind='component: "message-editor"'></div>

    <h4>Second instance, passing parameters</h4>
    <div 
		data-bind='component: {name: "message-editor", params: { initialText: "Hello, world!" }}'>
    </div>

    <script type="text/javascript">
        ko.components.register('message-editor', {
            viewModel: function(params) {
                this.text = ko.observable(params && params.initialText || '');
            },
            template: 'Message: <input data-bind="value: text" /> '
            + '(length: <span data-bind="text: text().length"></span>)'
        });

        ko.applyBindings();

		// Lưu ý: Sử dụng mà không có phần tử containercomponent

		// <!-- ko component: "message-editor" -->
		// <!-- /ko -->

		// <!-- ko component: {
		// 	name: "message-editor",
		// 	params: { initialText: "Hello, world!", otherParam: 123 }
		// } -->
		// <!-- /ko -->


    </script>
@endsection
