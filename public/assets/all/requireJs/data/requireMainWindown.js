requirejs.config({
    baseUrl: '/laravel1/public/assets/all/requireJs/data', //: windown
    // baseUrl: '/assets/all/requireJs/data',              //: ubuntu
    paths: {
        app: 'app',
        viewModal: 'viewModal',
        templates: 'templates',
        components: 'components'
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