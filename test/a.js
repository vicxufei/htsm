/**
 * Created by yefeng on 16/4/8.
 */

//function fun1(){
//  alert("it works");
//}
//
//fun1();


//第二种方法使用了块作用域来申明function防止污染全局变量，本质还是一样的.
// 当运行上面两种例子时不知道你是否注意到，alert执行的时候，html内容是一片空白的
// 即<span>body</span>并未被显示，当点击确定后，才出现，这就是JS阻塞浏览器渲染导致的结果。
//(function(){
//  function fun1(){
//    alert("it works 方式2");
//  }
//
//  fun1();
//})()


//使用requirejs的define来定义一个模块
define(function(){
  function fun1(){
    alert("it works");
  }

  fun1();
})



