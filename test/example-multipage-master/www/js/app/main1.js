define(function (require) {
    var $ = require('jquery'),
        lib = require('./lib'),
        controller = require('./controller/c1'),
        model = require('./model/m1');
        layer = require('layer');


  //A fabricated API to show interaction of
    //common and specific pieces.
    controller.setModel(model);
    $(function () {
      layer.alert("hello");

      controller.render(lib.getBody());
    });
});
