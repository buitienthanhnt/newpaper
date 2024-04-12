// AMD module whose value is a shared component viewmodel instance
define([
    'text!templates/demo5.html',
    'viewModal/demo5',
    'knockout',
    'components/AddressComp',
    'components/ListPerson'
], function (template, demo5, ko) {
    function Component(params = {}) {
        $(params.element).html(template);
        return ko.applyBindings(new demo5(params.initData), params.element);
    }
    return Component;
});