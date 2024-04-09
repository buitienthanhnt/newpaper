requirejs.config({
    // baseUrl: '/laravel1/public/assets/all/requireJs/data', //: windown
    baseUrl: '/assets/all/requireJs/data',                    //: ubuntu
    paths: {
        app: 'app',
        viewModal: 'viewModal',
        templates: 'templates',
        text: 'external/text',
    },
    shim: {
        'underscore': {
            exports: '_'
        },
        'jquery': {
            exports: '$'
        },
        // 'backbone': {
        //     deps: ['underscore', 'jquery'],
        //     exports: 'Backbone'
        // },
        // 'chaijquery': ['jquery', 'chai']
    },
    config: {
        text: {
            //Valid values are 'node', 'xhr', or 'rhino'
            env: 'rhino'
        }
    }
});