// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {

    function Viewmodel5(params) {
        var self = this;
        self.p = ko.observable(0);
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

        /**
         * function for get and set data for people.
         */
        self.getData = function () {
            $.ajax({
                url: `http://localhost/laravel1/public/api/test/getWriters?p=${self.p()}`,
                type: 'GET',
                success: function (result) {
                    console.log(result);
                    self.p(self.p() + 1)
                    self.people.push(...result);
                }.bind(this),
                error: function (e) {

                }
            })
        }
    }

    return Viewmodel5;
});