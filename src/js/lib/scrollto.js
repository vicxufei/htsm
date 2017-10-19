/**
 * Created by yefeng on 16/4/12.
 */
define(['jquery'],function($){
  //使用面向对象的方式来实现这个功能
  //构造函数首字母大写,用来和普通的函数区分
  function ScrollTo(opts){
    //默认参数用var定义一个变量写在这里?
    // No,因为这样一来每一个实例中都有这样一个私有的变量,
    // 其实是一种浪费

    //将用户传递进来的参数去覆盖默认参数,生成一个新的对象,并返回这个新的对象
    this.opts = $.extend({},ScrollTo.DEFAULTS,opts);

    this.$el = $('html,body');

    //将要实现的方法添加到构造函数的原型上,使内存中只保存一份所有的方法
    ScrollTo.prototype.move = function (){
      //使用局部变量缓存参数
      var opts = this.opts;
          dest = opts.dest;
      if($(window).scrollTop() != dest){
        if (!this.$el.is(':animated')){
          this.$el.animate({
            scrollTop:dest
          },opts.speed);
        }
      }
    };
    ScrollTo.prototype.go = function (){
      var dest = this.opts.dest;
      if($(window).scrollTop() != dest){
        this.$el.scrollTop(dest);
      }
    };

  }
  ScrollTo.DEFAULTS = {
    dest: 0,
    speed: 800
  };

  return {
    ScrollTo: ScrollTo
  }

});