@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <ul data-bind="foreach: places">
        <li data-bind="text: $data, event: { mouseover: $parent.logMouseOver }"> </li>
    </ul>
    <p>You seem to be interested in: <span data-bind="text: lastInterest"> </span></p>

    <script type="text/javascript">
        function MyViewModel() {
            var self = this;
            self.lastInterest = ko.observable();
            self.places = ko.observableArray(['London', 'Paris', 'Tokyo']);

            // The current item will be passed as the first parameter, so we know which place was hovered over
            self.logMouseOver = function(place) {
                self.lastInterest(place);
            }
        }
        ko.applyBindings(new MyViewModel());

		// Lưu ý 1: Chuyển "mục hiện tại" làm tham số cho hàm xử lý của bạn
		// Lưu ý 2: Truy cập vào đối tượng sự kiện hoặc chuyển thêm tham số
		// Lưu ý 3: Cho phép hành động mặc định
		// Lưu ý 4: Ngăn sự kiện sủi bọt
		// Lưu ý 5: Tương tác với jQuery
    </script>
@endsection
