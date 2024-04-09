// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {
    function Viewmodel2(params) {
        var self = this;
        self.age = params.age
    }

    return Viewmodel2;
});