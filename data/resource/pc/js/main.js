requirejs.config({
  //By default load any module IDs from js/lib
  baseUrl: 'http://mall.htths.com/data/resource/pc/js',
  //except, if the module ID starts with "app",
  //load it from the js/app directory. paths
  //config is relative to the baseUrl, and
  //never includes a ".js" extension since
  //the paths config could be for a directory.

  //"shim": {
  //  'jquery.fly': {
  //    deps: ['jquery'],
  //    exports: 'jQuery.fn.fly'
  //  }
  //},
  //paths: {
  //  jquery: 'jquery',
  //  'jquery.fly': 'fly/jquery.fly'
  //}

  paths: {
    jquery: 'lib/jquery-1.9.1'
  }

});

// Start the main app logic.
requirejs(['jquery','test','search_goods','common','yf1'], function ($) {
    //jQuery, canvas and the app/sub module are all
    //loaded and can be used here now.
    //鼠标经过弹出图片信息gyf


  });