// AMD module whose value is a shared component viewmodel instance
define(['text!templates/demo5.html', 'viewModal/demo5', 'knockout', 'text!templates/item.html'], function (template, demo5, ko, item) {
    function Component(params = {}) {
        $(params.element).html(template);
        return ko.applyBindings(new demo5(params.initData), params.element);
    }

    ko.components.register('message-editor', {
        viewModel: function (params) {
            return params.value;
        },
        template: item
    });

    return Component;
});