/**
 * Created by yefeng on 16/4/22.
 */
require.config({
  baseUrl:"./",
  paths:{
    jquery:"jquery-1.12.3.min",
    layer:"layer/layer"
  },
  shim:{
    "layer":["jquery"]
  }
});

require(["jquery","layer"],function($,layer){
  layer.alert("hello");
});