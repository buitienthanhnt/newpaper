define(function (require) {
    // Load any app-specific modules
    // with a relative require call,
    // like:
    var messages = require('app/messages');

    // Load library/vendor modules using
    // full IDs, like:

    return {
        show: function(){
            messages.getHello();
            return 'qweqweqwe'
        }
    }
});