requirejs.config({
    // baseUrl: '/laravel1/public/assets/all/requireJs/data', //: windown
    baseUrl: '/assets/all/requireJs/data',                    //: ubuntu
    paths: {
        app: 'app',
        viewModal: 'viewModal',
        templates: 'templates',
    },
    shim: {
        'underscore': {
            exports: '_'
        },
        'jquery': {
            exports: '$'
        },
    },
});