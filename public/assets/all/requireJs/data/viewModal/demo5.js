// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {

    function Viewmodel5(params) {
        var self = this;
        self.people = ko.observableArray(params.people);

        /**
         * remove an people 
         */
        self.onDelete = function (item) {
            self.people.remove(item)
        }

        /**
         * 
         *  add new people
         */
        self.adddItem = function (item) {
            self.people.push(item)
        }
    }

    return Viewmodel5;
});