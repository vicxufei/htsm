/**
 * Created by yefeng on 16/5/8.
 */
define(["require", "exports", "module", "jquery", "lib/gallery/utils/1.0.0/image"], function(require, n, e) {
  "use strict";
  var jquery = require("jquery")
    , a = require("lib/gallery/utils/1.0.0/image");
  jquery.fn.slide = function(i) {
    function n(i) {
      function n() {
        l.eq(6).addClass("hover").siblings("a").removeClass("hover");
        var i = u.eq(m)
          , n = u.eq(d)
          , e = i.attr(h);
        i.show(),
        e && a.load(e, function() {
          i.css({
            "background-image": "url(" + e + ")"
          }),
            i.removeAttr(h)
        }),
        d != m && n.stop().animate({
          opacity: 0,
          "z-index": 1
        }, 1e3),
          i.stop().animate({
            opacity: 1,
            "z-index": 9
          }, 1e3),
          d = m
      }
      function e() {
        o.duration > 0 && (i.mouseenter(function() {
          f = !0
        }),
          i.mouseleave(function() {
            f = !1
          }),
          setInterval(function() {
            f || s(!0)
          }, o.duration))
      }
      function s(i) {
        i ? (m++,
        m >= g && (m = 0)) : (m--,
        0 > m && (m = g - 1)),
          n()
      }
      function r(i) {
        var n = t('<a class="arrow-l jImgLeft" href="javascript:;"><em></em><i class="icon iconfont"></i></a><a class="arrow-r jImgRight" href="javascript:;"><em></em><i class="icon iconfont"></i></a>').appendTo(i)
          , e = n.filter(".jImgLeft").hide()
          , a = n.filter(".jImgRight").hide();
        e.on("click", function() {
          s(!1)
        }),
          a.on("click", function() {
            s(!0)
          }),
          i.mouseenter(function() {
            e.show(),
              a.show()
          }),
          i.mouseleave(function() {
            e.hide(),
              a.hide()
          })
      }
      function c() {
        i.find(".slide-list").css({
          background: "none"
        })
      }
      var u = i.find(".slide-list li");
      if (!u || 0 != u.length) {
        var l = i.find(".slide-num a")
          , f = !1
          , d = 0
          , m = 0
          , h = o.attr
          , g = l.length;
        2 > g && l.hide(),
          l.mouseenter(function() {
            m = t(this).index(),
              n()
          }),
        o.addBtn && r(i),
          n(),
          c(),
          e()
      }
    }
    var e = {
      attr: "data-img",
      duration: 5e3,
      addBtn: !1,
      callback: function() {}
    }
      , o = t.extend({}, e, i || {});
    t(this).each(function() {
      var i = t(this);
      n(i)
    })
  }
});