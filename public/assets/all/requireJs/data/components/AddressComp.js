// AMD module whose value is a shared component viewmodel instance
define(['text!templates/form.html', 'viewModal/AddressForm', 'knockout'], function (template, AddressForm, ko) {
    /**
     * form for add new person
     */
    ko.components.register('adress-form', {
        viewModel: AddressForm,
        template: template
    });
});