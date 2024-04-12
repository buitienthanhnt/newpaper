// AMD module whose value is a shared component viewmodel instance
define([
    'text!templates/demo5.html',
    'viewModal/demo5',
    'knockout',
    'components/AddressComp',
    'components/ListPerson'
], function (template, demo5, ko) {
    function Component(params = {}) {
        var viewModel = new demo5(params.initData);
        /**
         * first for fecth data.
         */
        viewModel.getData();
        $(params.element).html(template);
        /**
         * apply viewmodel to element target
         */
        return ko.applyBindings(viewModel, params.element);
    }
    return Component;

});