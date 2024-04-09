// AMD module whose value is a shared component viewmodel instance
// tạo 1 viewmodel và applyBindings cho thẻ element template.
define(['require', 'knockout', 'text!templates/demo4.html'], function (require, ko, html4) {
    "use strict";

    function Component(params = {}) {
        console.log(html4);
        let viewModel = require(params.viewModel);
        // let templates = require('text!templates/demo4.html');
        // console.log(123, templates);
        // domReady(function () {
            // let templates = require('text!templates/demo4.html');
        //     console.log(templates);
        //     //This function is called once the DOM is ready.
        //     //It will be safe to query the DOM and manipulate
        //     //DOM nodes in this function.
        //   });
        // var compiledTemplate = _.template(html4);
        // console.log(compiledTemplate);
        // compiledTemplate({name: 'moe'});
        return ko.applyBindings(new viewModel(params.initData), params.element);
    }
    return Component;
});

// Các lệnh gọi tham số nội tuyến chỉ có thể chạy không đồng bộ cho các mô-đun chưa được tải, 
// như trường hợp của bạn. Nguyên tắc là (cũng lưu ý là trong một mảng):requireurl
// https://code.tutsplus.com/require-js-explained--CRS-200144c/using-the-text-plugin