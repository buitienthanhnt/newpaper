// AMD module whose value is a shared component viewmodel instance
// tạo 1 viewmodel và applyBindings cho thẻ element template.
define(['require', 'knockout'], function (require, ko) {

    function Component(params = {}) {
        let viewModel = require(params.viewModel);
        return ko.applyBindings(new viewModel(params.initData), params.element);
    }
    return Component;
});