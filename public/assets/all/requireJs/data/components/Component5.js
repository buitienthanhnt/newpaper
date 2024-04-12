// AMD module whose value is a shared component viewmodel instance
define(['text!templates/demo5.html', 'viewModal/demo5', 'knockout', 'text!templates/item.html', 'text!templates/form.html'], function (template, demo5, ko, item, form) {
    function Component(params = {}) {
        $(params.element).html(template);
        return ko.applyBindings(new demo5(params.initData), params.element);
    }

    ko.components.register('adress-form', {
        viewModel: function (params) {
            return params.model;
        },
        template: form
    });

    ko.components.register('message-editor', {
        viewModel: function (params) {
            return params.value;
        },
        template: item
    });

    return Component;
});