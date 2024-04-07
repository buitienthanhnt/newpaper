@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form>
        <p>
            <input type='checkbox' data-bind="checked: hasCellphone" />
            I have a cellphone
        </p>
        <p>
            Your cellphone number:
            <input type='text' data-bind="value: cellphoneNumber, enable: hasCellphone" />
        </p>
    </form>

    <script type="text/javascript">
        var viewModel = {
            hasCellphone: ko.observable(false),
            cellphoneNumber: ""
        };
        ko.applyBindings(viewModel);

        // Lưu ý 1: Chuyển "mục hiện tại" làm tham số cho hàm xử lý của bạn
        // Lưu ý 2: Truy cập vào đối tượng sự kiện hoặc chuyển thêm tham số
        // Lưu ý 3: Cho phép hành động mặc định
        // Lưu ý 4: Ngăn sự kiện sủi bọt
        // Lưu ý 5: Tương tác với jQuery
    </script>
@endsection
