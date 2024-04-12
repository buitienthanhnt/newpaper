// AMD module whose value is a shared component viewmodel instance
define(['text!templates/item.html', 'knockout'], function (template, ko) {
    /**
     * form for add new person
     */
    ko.components.register('message-editor', {
        viewModel: function (params) {
            return params.model;
        },
        template: template
    });
});