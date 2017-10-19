//The build will inline common dependencies into this file.

//For any third party dependencies, like jQuery, place them in the lib folder.

//Configure loading modules from the lib directory,
//except for 'app' ones, which are in a sibling
//directory.
requirejs.config({
  //baseUrl: '.',
  paths: {
    //app: '../app',
    jquery:'../lib/jquery-1.12.3.min',
    validate:'../lib/jquery.validate.min',
    countdown:'../lib/jquery.countdown.min',
    template:'../lib/template',
    jscookie:'../lib/js.cookie',
    scrollto:'../lib/scrollto',
    region:'../lib/region',
    //layer:'../lib/layer/layer',
    minibar:'../common/minibar',
    header:'../common/header'
  },
  shim: {
    'template': {
      exports: 'template'
    },
    'jscookie': {
      exports: 'jscookie'
    },
    layer: {
      deps: ['jquery'],
      exports: 'layer'
    },
    countdown: {
      deps: ['jquery'],
      exports: 'countdown'
    },
    region: {
      deps: ['jquery'],
      exports: 'region'
    }
  }
});


