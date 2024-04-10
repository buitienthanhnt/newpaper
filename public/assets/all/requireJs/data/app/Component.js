// AMD module whose value is a shared component viewmodel instance
// tạo 1 viewmodel và applyBindings cho thẻ element template.
define(['require', 'knockout', 'text'], function (require, ko, text) {
    function Component(params = {}) {
        let viewModel = require(params.viewModel);
        $(params.element).html(params.template);
        return ko.applyBindings(new viewModel(params.initData), params.element);
    }
    return Component;
});

// Các lệnh gọi tham số nội tuyến chỉ có thể chạy không đồng bộ cho các mô-đun chưa được tải,
// như trường hợp của bạn. Nguyên tắc là (cũng lưu ý là trong một mảng):requireurl
// https://code.tutsplus.com/require-js-explained--CRS-200144c/using-the-text-plugin