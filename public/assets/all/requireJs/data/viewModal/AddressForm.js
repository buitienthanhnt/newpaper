// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {
    function AddressForm(params) {
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

        self.getData = function () {
            params.model.getData();
        }
    }
    return AddressForm
});