// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {

    function Viewmodel5(params) {
        var self = this;
        self.age = ko.observable(params.age);
        self.name = ko.observable(params.name);
        self.fullName = ko.computed(function () { // gán thuộc tính phụ thuộc
            return self.name() + " " + self.age();
        });

        self.people = ko.observableArray(params.people);

        self.onPress = function () {
            self.age(24);
            self.name('after onClick');
            self.people.push(
                {
                    name: 'tha nan add'
                }
            )
        }
    }

    return Viewmodel5;
});