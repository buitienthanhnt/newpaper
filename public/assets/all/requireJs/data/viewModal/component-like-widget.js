// AMD module whose value is a shared component viewmodel instance
define(['knockout'], function (ko) {
    function MyViewModel(params = {}) {
        var self = this;
        self.address = 'nam tan- truc noi -truc ninh- nam dinh';
        self.name = params.name
    }

    // return MyViewModel;
    return ko.applyBindings(new MyViewModel({name: 'tha nan 123'}), $("#demo-comp3")[0]);
});