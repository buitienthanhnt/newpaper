@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <ul data-bind="foreach: places">
        <li>
            <span data-bind="text: $data.name"></span>
            <button data-bind="click: $parent.removePlace">Remove</button>
        </li>
    </ul>

    <script type="text/javascript">
       function MyViewModel() {
         var self = this;
         self.places = ko.observableArray([
			{name: 'London'}, 
			{name: 'paris'}, 
			{name: 'genever'}, 
			{name: 'bernin'}, 
		]);
 
         // The current item will be passed as the first parameter, so we know which place to remove
         self.removePlace = function(place) {
			console.log('====================================');
			console.log(place);
             self.places.remove(place)
         }
     }
     ko.applyBindings(new MyViewModel());


	    // Lưu ý 1: Chuyển "mục hiện tại" làm tham số cho hàm xử lý của bạn
		//  Lưu ý 2: Truy cập vào đối tượng sự kiện hoặc chuyển thêm tham số
			// <button data-bind="click: myFunction">
			// 	Click me
			// </button>
			// var viewModel = {
			//     myFunction: function(data, event) {
			//         if (event.shiftKey) {
			//             //do something different when user has shift key down
			//         } else {
			//             //do normal action
			//         }
			//     }
			// };
			// ko.applyBindings(viewModel);
		// truyefn nhieu tham so hown:
			// <button data-bind="click: function(data, event) { myFunction('param1', 'param2', data, event) }">
			// 	Click me
			// </button>
		//  Lưu ý 3: Cho phép hành động nhấp chuột mặc định
		//  Lưu ý 4: Ngăn sự kiện sủi bọt
		//  Lưu ý 5: Tương tác với jQuery

    </script>
@endsection
