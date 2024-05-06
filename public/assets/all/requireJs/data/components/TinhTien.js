// AMD module whose value is a shared component viewmodel instance
define([
    'text!templates/tinhtien.html',
    'viewModal/Tinhtien',
    'knockout',
], function (template, Tinhtien, ko) {
    function Component(params = {}) {
        var viewModel = new Tinhtien(params.initData);
        /**
         * first for fecth data.
         */
        
        $(params.element).html(template);
        /**
         * apply viewmodel to element target
         */
        return ko.applyBindings(viewModel, params.element);
    }
    return Component;

});