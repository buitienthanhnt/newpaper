// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {

    function Viewmodel5(params) {
        var self = this;
        self.message = ko.observable();
        self.message.subscribe(function () {
            if (self.message()) {
                setTimeout(function () {
                    self.message(null)
                }, 2000)
            }

        })
        self.messageType = ko.observable('');
        self.age = ko.observable(params.age);
        self.name = ko.observable(params.name);
        self.fullName = ko.computed(function () { // gán thuộc tính phụ thuộc
            return self.name() + " " + self.age();
        });

        self.people = ko.observableArray(params.people);

        /**
         * add new people
         */
        self.onPress = function () {
            // self.age(24);
            // self.name('after onClick');
            if (self.name() && self.age()) {
                self.people.push(
                    {
                        name: self.name(),
                        age: self.age()
                    }
                )
                self.message('added for new people!')
                self.messageType('green')
                self.name(null);
                self.age(null);
            } else {
                self.message('add fail, please update value!')
                self.messageType('red')
            }
        }

        /**
         * remove an people 
         */
        self.onDelete = function (item) {
            self.people.remove(item)
        }


    }

    return Viewmodel5;
});