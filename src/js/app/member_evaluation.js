/**
 * Created by yefeng on 16/5/3.
 */

define(function (require) {
  var $ = require('jquery');
  var template = require('template');
  var header = require('header');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    var starElement = $(".iconfont-star-o");
    var hoverOnStar = function(_this,currentScore){
      var parentElement = _this.parent();
      var oldScoreStr = 'starts-' + parentElement.attr('score');
      parentElement.removeClass(oldScoreStr);
      parentElement.attr('score',currentScore);
      var currentScoreStr = 'starts-' + currentScore;
      parentElement.addClass(currentScoreStr);
    };
    starElement.hover(function(){
      var _this = $(this);
      var currentScore = _this.attr('lv');
      hoverOnStar(_this,currentScore)
    },function(){
      var _this = $(this);
      var settedScore = _this.siblings('input').val();
      hoverOnStar(_this,settedScore)
    });

    starElement.click(function(){
      var _this = $(this);
      var currentScore = _this.attr('lv');
      _this.siblings('input').val(currentScore);
    });

    $("#jSubmitComment").click(function(){
      $('#evalform').submit();
    });

  }); //DOM ready
});

