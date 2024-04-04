@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
Today's message is: <span data-bind="text: myMessage"></span>
 
<script type="text/javascript">
    var viewModel = {
        myMessage: ko.observable() // Initially blank
    };
    // html sẽ vẫn được giữ nguyên dạng text mà không được chuyển đổi.
    viewModel.myMessage("<i>Hello, world!</i>"); // Text appears
    ko.applyBindings(viewModel);
    // lưu ý: 
    
    // <select data-bind="foreach: items">
    //     <option>Item <span data-bind="text: name"></span></option>
    // </select> // sẽ không nhận được name của item

    // Để xử lý vấn đề này, bạn có thể sử dụng cú pháp không chứa vùng chứa , dựa trên thẻ nhận xét.

    // <select data-bind="foreach: items">
    //     <option>Item <!--ko text: name--><!--/ko--></option>
    // </select>

    // Nhận xét <!--ko-->và <!--/ko-->đóng vai trò là điểm đánh dấu bắt đầu/kết thúc, 
    // xác định “phần tử ảo” có chứa đánh dấu bên trong. 
    // Knockout hiểu cú pháp phần tử ảo này và liên kết như thể bạn có phần tử vùng chứa thực.
</script>
@endsection
