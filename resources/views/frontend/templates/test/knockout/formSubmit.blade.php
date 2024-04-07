@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form data-bind="submit: doSomething">
        <button type="submit">Submit</button>
    </form>

    <script type="text/javascript">
        var viewModel = {
            doSomething: function(formElement) {
                // ... now do something
            }
        };
        ko.applyBindings(new MyViewModel());

        // Lưu ý 1: Chuyển "mục hiện tại" làm tham số cho hàm xử lý của bạn
        // Lưu ý 2: Truy cập vào đối tượng sự kiện hoặc chuyển thêm tham số
        // Lưu ý 3: Cho phép hành động mặc định
        // Lưu ý 4: Ngăn sự kiện sủi bọt
        // Lưu ý 5: Tương tác với jQuery
    </script>
@endsection
