// AMD module whose value is a shared component viewmodel instance
define(['text!templates/demo5.html', 'viewModal/demo5', 'knockout', 'text!templates/item.html', 'text!templates/form.html'], function (template, demo5, ko, item, form) {
    function Component(params = {}) {
        $(params.element).html(template);
        return ko.applyBindings(new demo5(params.initData), params.element);
    }

    /**
     * form for add new person
     */
    ko.components.register('adress-form', {
        viewModel: function (params) {
            self = this;
            /**
             * attribute of people
             */
            self.id = null;
            self.age = ko.observable(params.age);
            self.name = ko.observable(params.name);
            self.fullName = ko.computed(function () { // gán thuộc tính phụ thuộc
                return self.name() + " " + self.age();
            });

            /**
             * observer message.
             */
            self.messageType = ko.observable('');
            self.message = ko.observable();
            self.message.subscribe(function () {
                if (self.message()) {
                    setTimeout(function () {
                        self.message(null)
                    }, 2000)
                }

            });

            self.onPress = function () {
                if (self.name() && self.age()) {
                    params.model.adddItem(
                        {
                            name: self.name(),
                            age: self.age(),
                            id: params.model.people().length + 1
                        }
                    );
                    self.message('added for new people!')
                    self.messageType('green')
                    self.name(null);
                    self.age(null);
                } else {
                    self.message('add fail, please update value!')
                    self.messageType('red')
                }
            }
        },
        template: form
    });

    /**
     * list of all peoples.
     */
    ko.components.register('message-editor', {
        viewModel: function (params) {
            return params.model;
        },
        template: item
    });

    return Component;
});