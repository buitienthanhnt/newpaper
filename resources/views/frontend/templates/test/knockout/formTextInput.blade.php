@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form>
		<p>Login name: <input data-bind="textInput: userName" /></p>
		<p>Password: <input type="password" data-bind="textInput: userPassword" /></p>
    </form>
	<button data-bind="click: sub"> submit</button>

    <script type="text/javascript">
		// gan giong input - value 
        var viewModel = {
            userName: ko.observable("324"), // Initially blank
            userPassword: ko.observable("abc"), // Prepopulate
			sub: function(){
				console.log('====================================');
				console.log(this.userName(), this.userPassword());
				console.log('====================================');
			}
        };

        ko.applyBindings(viewModel);
    </script>
@endsection
