@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form>
        <p>Login name: <input data-bind="value: userName" /></p>
        <p>Password: <input type="password" data-bind="value: userPassword" /></p>
    </form>
	<button data-bind="click: sub"> submit</button>

    <script type="text/javascript">
        var viewModel = {
            userName: ko.observable(""), // Initially blank
            userPassword: ko.observable("abc"), // Prepopulate
			sub: function(){
				console.log('====================================');
				console.log(this.userName(), this.userPassword());
				console.log('====================================');
			}
        };

        ko.applyBindings(viewModel);

        // Nếu tham số này là một giá trị quan sát được, ràng buộc sẽ cập nhật giá trị của phần tử bất cứ khi nào giá trị thay đổi. 
		// Nếu tham số không thể quan sát được, nó sẽ chỉ đặt giá trị của phần tử một lần và sẽ không cập nhật lại sau.

		// Lưu ý 1: Nhận cập nhật giá trị ngay lập tức từ đầu vào
		// Lưu ý 2: Làm việc với danh sách thả xuống (ví dụ: phần tử)<select>
			// Sử dụng với các yếu tố    valueAllowUnset<select>
				// <p>
				// 	Select a country:
				// 	<select data-bind="options: countries,
				// 					optionsCaption: 'Choose one...',
				// 					value: selectedCountry,
				// 					valueAllowUnset: true"></select>
				// </p>
		// Lưu ý 3: Cập nhật giá trị thuộc tính quan sát được và không quan sát được
		// Lưu ý 4: Sử dụng liên kết với ràng buộcvaluechecked
		// Lưu ý 5: Tương tác với jQuery
    </script>
@endsection
