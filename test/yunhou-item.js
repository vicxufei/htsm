/**
 * Created by yefeng on 16/4/13.
 */
define("pub/js/core/bbg", ["require", "exports", "module", "jquery", "lib/core/1.0.0/utils/util", "lib/gallery/utils/1.0.0/image", "./ajax"], function(t, e, i) {
  "use strict";
  var o, n = t("jquery"), a = t("lib/core/1.0.0/utils/util"), r = t("lib/gallery/utils/1.0.0/image"), s = a.deprecate;
  e.IMG = {
    getImgByType: s(function(t, e) {
      return r.getImageURL(t, e)
    }, "#.getImgByType(): Use gallery/utils/image instead"),
    getSizeByUri: s(function(t) {
      var e = r.parseSize(t);
      return e && {
          w: e.width,
          h: e.height
        }
    }, "#.getSizeByUri(): Use gallery/utils/image instead"),
    load: s(function(t, e, i) {
      return r.load.apply(r, arguments)
    }, "#.load(): Use gallery/utils/image instead")
  },
    e.isSupport = function() {
      var t = !-[1] && !window.XMLHttpRequest
        , e = t;
      e && n(document.body).prepend('<div style="display:block;text-align:center;padding: 20px 0;background-color: #fed5c7;text-align: center;font-size: 14px;color: #bf440a;">亲，你当前的浏览版本太低，部分功能或者内容可能显示不正常，请升级或者更换你的浏览器，换得更好的浏览体验。</div>')
    }
    ,
    Date.prototype.format = function(t) {
      var e, i;
      t || (t = "yyyy-MM-dd hh:mm:ss"),
        e = {
          "M+": this.getMonth() + 1,
          "d+": this.getDate(),
          "h+": this.getHours(),
          "m+": this.getMinutes(),
          "s+": this.getSeconds(),
          "q+": Math.floor((this.getMonth() + 3) / 3),
          S: this.getMilliseconds()
        },
      /(y+)/.test(t) && (t = t.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length)));
      for (i in e)
        new RegExp("(" + i + ")").test(t) && (t = t.replace(RegExp.$1, 1 == RegExp.$1.length ? e[i] : ("00" + e[i]).substr(("" + e[i]).length)));
      return t
    }
    ,
    o = e.AJAX = {},
    a.each(t("./ajax"), function(t, e) {
      "function" == typeof t && (o[e] = s(t, "#.ajax." + e + "(): Use pub/module/ajax instead"))
    }),
    window.BBG = e
}),
  define("url", ["require", "exports", "module", "bbg"], function(t, e, i) {
    t("bbg");
    BBG.Path = {
      cart: "http://api.cart.yunhou.com/",
      mall: "http://api.mall.yunhou.com/",
      item: "http://item.yunhou.com/",
      addr: "http://www.yunhou.com/",
      settlement: "http://api.cart.yunhou.com/",
      baseUrl: "http://" + window.location.host + "/page1/ajax/",
      shopAddress: "http://shop.yunhou.com/"
    },
      BBG.URL = {
        List: {
          price: "/search/getAsyncData",
          getPrefer: "http://search.yunhou.com/product/productInfo"
        },
        Img: {
          blank: "//static5.bubugao.com/public/img/blank.gif"
        },
        Login: {
          minLogin: "https://ssl.yunhou.com/login/login-min.php",
          login: "https://ssl.yunhou.com/login/login.php"
        },
        Time: {
          current: "http://api.mall.yunhou.com/Time"
        },
        Cart: {
          get: BBG.Path.cart + "cart/get",
          add: BBG.Path.cart + "cart/add",
          fastBuy: BBG.Path.cart + "cart/fastBuy",
          checked: BBG.Path.cart + "cart/checked",
          update: BBG.Path.cart + "cart/update",
          del: BBG.Path.cart + "cart/del",
          emptyCart: BBG.Path.cart + "cart/clear",
          setShippingMethod: BBG.Path.cart + "cart/selectDelivery"
        },
        actCart: {
          get: BBG.Path.cart + "actCart/get",
          update: BBG.Path.cart + "actCart/update",
          del: BBG.Path.cart + "actCart/remove",
          price: BBG.Path.mall + "product/productInfo"
        },
        Goods: {
          col: BBG.Path.mall + "product/addfavorite",
          hotRank: BBG.Path.item + "product/hotrank",
          checkFavorite: BBG.Path.mall + "product/checkFavorite",
          arrival: BBG.Path.mall + "product/subscribe"
        },
        Search: {
          suggest: "http://api.search.yunhou.com/bubugao-search-server/api/search",
          all: "http://search.yunhou.com",
          keyword: "http://api.mall.yunhou.com/HotKeyword/getHotKeywords"
        },
        Shop: {
          price: "http://shop.yunhou.com/shop/getAsyncData",
          col: BBG.Path.mall + "shop/addfavorite",
          getListIconInfo: "http://search.yunhou.com/product/labelInfo"
        },
        MinCart: {
          get: BBG.Path.cart + "cart/miniGet",
          getCount: BBG.Path.cart + "cart/getSimple",
          del: BBG.Path.cart + "cart/miniDel"
        },
        Item: {
          cms: BBG.Path.item + "index/ajaxGetCommentData",
          history: BBG.Path.item + "index/ajaxGetHistoryList",
          info: BBG.Path.item + "index/ajaxGetData"
        },
        Addr: {
          selectedUrl: BBG.Path.addr + "api/getUserRegion",
          changeCallBackUrl: BBG.Path.addr + "api/setUserRegion",
          url: BBG.Path.addr + "api/getRegion/jsonp/"
        },
        settlement: {
          cart: "http://cart.yunhou.com/cart.shtml",
          buyAtOnce: "http://cart.yunhou.com/buy-at-once.shtml",
          buyNow: "http://cart.yunhou.com/buy-now.shtml",
          getSettlementList: BBG.Path.settlement + "ordercheck/get",
          getMention: BBG.Path.settlement + "address/getZtd",
          setMention: BBG.Path.settlement + "address/selectSelfPoint",
          setElectricInfo: BBG.Path.settlement + "ordercheck/saveJlSaler",
          saveAddr: BBG.Path.settlement + "address/saveAddr",
          selectAddr: BBG.Path.settlement + "address/selectAddr",
          setDefaultAddr: BBG.Path.settlement + "address/setDefAddrs",
          selectIdCard: BBG.Path.settlement + "ordercheck/selectIdCard",
          saveInvoiceInfo: BBG.Path.settlement + "tax/saveTax",
          noInvoice: BBG.Path.settlement + "tax/cancelTax",
          deleteAddr: BBG.Path.settlement + "address/delAddrs",
          useOffers: BBG.Path.settlement + "coupon/useCoupon",
          cancelOffers: BBG.Path.settlement + "coupon/cancelCoupon",
          couponList: BBG.Path.settlement + "coupon/userCoupons",
          shopDeliveryList: BBG.Path.settlement + "ordercheck/shopDelivery",
          selectDeliveryType: BBG.Path.settlement + "ordercheck/selectDelivery",
          leaveMsg: BBG.Path.settlement + "ordercheck/saveMemo",
          subOrder: BBG.Path.settlement + "order/create",
          getInvoiceList: "http://api.cart.yunhou.com/tax/taxContentList",
          addReferrer: BBG.Path.settlement + "ordercheck/saveRecommender",
          verified: {
            add: BBG.Path.settlement + "ordercheck/saveIdCard",
            update: BBG.Path.settlement + "ordercheck/getIdCard",
            updateName: BBG.Path.settlement + "ordercheck/saveIdCard",
            del: BBG.Path.settlement + "ordercheck/delIdCard"
          }
        },
        getUserInfo: BBG.Path.mall + "member/getContact",
        UC: {
          getVipInfo: "http://i.yunhou.com/vip-card/info",
          login: "https://ssl.yunhou.com/login/login.php",
          regist: "https://ssl.yunhou.com/login/reg.php",
          index: "http://i.yunhou.com/",
          logout: "https://ssl.yunhou.com/bubugao-passport/logout"
        },
        mendian: "http://shop.yunhou.com/shop/deliveryList"
      }
  }),
  define("pub/js/ui/dialog/popup", ["require", "jquery"], function(t) {
    function e() {
      this.destroyed = !1,
        this.__popup = i("<div />").attr({
          tabindex: "-1"
        }).css({
          display: "none",
          position: "absolute",
          outline: 0
        }).html(this.innerHTML).appendTo("body"),
        this.__backdrop = i("<div />"),
        this.node = this.__popup[0],
        this.backdrop = this.__backdrop[0],
        o++
    }
    var i = t("jquery")
      , o = 0
      , n = !("minWidth" in i("html")[0].style)
      , a = !n;
    return i.extend(e.prototype, {
      node: null ,
      backdrop: null ,
      fixed: !1,
      destroyed: !0,
      open: !1,
      returnValue: "",
      autofocus: !0,
      align: "bottom left",
      backdropBackground: "#000",
      backdropOpacity: .7,
      innerHTML: "",
      className: "ui-popup",
      autoTime: 3e3,
      show: function(t) {
        var e, o;
        return "number" == typeof arguments[0] && (t = void 0),
          this.destroyed ? this : (e = this,
            o = this.__popup,
            this.__activeElement = this.__getActive(),
            this.open = !0,
            this.follow = t && i(t)[0] || this.follow,
          this.__ready || (o.addClass(this.className),
          this.modal && this.__lock(),
          o.html() || o.html(this.innerHTML),
          n || i(window).on("resize", this.__onresize = function() {
              e.reset()
            }
          ),
            this.__ready = !0),
            o.attr("role", this.modal ? "alertdialog" : "dialog").css("position", this.fixed ? "fixed" : "absolute"),
            this.__backdrop.show(),
            this.reset(),
            o.show().addClass(this.className + "-show"),
            this.focus(),
            this.__dispatchEvent("show"),
            this)
      },
      showModal: function() {
        return this.modal = !0,
          this.show.apply(this, arguments)
      },
      time: function() {
        var t, e, i = this.autoTime;
        "number" == typeof arguments[0] ? i = arguments[0] : "number" == typeof arguments[1] && (i = arguments[1]),
          t = this.show.apply(this, arguments),
        i && (e = this,
          function(t) {
            setTimeout(function() {
              t.remove()
            }, i)
          }(e))
      },
      close: function(t) {
        return !this.destroyed && this.open && (void 0 !== t && (this.returnValue = t),
          this.__popup.hide().removeClass(this.className + "-show"),
          this.__backdrop.hide(),
          this.open = !1,
          this.blur(),
          this.__dispatchEvent("close")),
          this
      },
      remove: function() {
        if (this.destroyed)
          return this;
        this.__dispatchEvent("beforeremove"),
        e.current === this && (e.current = null ),
          this.__unlock(),
          this.__popup.remove(),
          this.__backdrop.remove(),
        n || i(window).off("resize", this.__onresize),
          this.__dispatchEvent("remove");
        for (var t in this)
          delete this[t];
        return this
      },
      reset: function() {
        var t = this.follow;
        return t ? this.__follow(t) : this.__center(),
          this.__dispatchEvent("reset"),
          this
      },
      focus: function() {
        var t, o = this.node, n = e.current;
        return n && n !== this && n.blur(!1),
        i.contains(o, this.__getActive()) || (t = this.__popup.find("[autofocus]")[0],
          !this._autofocus && t ? this._autofocus = !0 : t = o,
          this.__focus(t)),
          e.current = this,
          this.__popup.addClass(this.className + "-focus"),
          this.__zIndex(),
          this.__dispatchEvent("focus"),
          this
      },
      blur: function() {
        var t = this.__activeElement
          , e = arguments[0];
        return e !== !1 && this.__focus(t),
          this._autofocus = !1,
          this.__popup.removeClass(this.className + "-focus"),
          this.__dispatchEvent("blur"),
          this
      },
      addEventListener: function(t, e) {
        return this.__getEventListener(t).push(e),
          this
      },
      removeEventListener: function(t, e) {
        var i, o = this.__getEventListener(t);
        for (i = 0; i < o.length; i++)
          e === o[i] && o.splice(i--, 1);
        return this
      },
      __getEventListener: function(t) {
        var e = this.__listener;
        return e || (e = this.__listener = {}),
        e[t] || (e[t] = []),
          e[t]
      },
      __dispatchEvent: function(t) {
        var e, i = this.__getEventListener(t);
        for (this["on" + t] && this["on" + t](),
               e = 0; e < i.length; e++)
          i[e].call(this)
      },
      __focus: function(t) {
        try {
          this.autofocus && !/^iframe$/i.test(t.nodeName) && t.focus()
        } catch (e) {}
      },
      __getActive: function() {
        var t, e, i;
        try {
          return t = document.activeElement,
            e = t.contentDocument,
            i = e && e.activeElement || t
        } catch (o) {}
      },
      __zIndex: function() {
        var t = e.zIndex++;
        this.__popup.css("zIndex", t),
          this.__backdrop.css("zIndex", t - 1),
          this.zIndex = t
      },
      __center: function() {
        var t = this.__popup
          , e = i(window)
          , o = i(document)
          , n = this.fixed
          , a = n ? 0 : o.scrollLeft()
          , r = n ? 0 : o.scrollTop()
          , s = e.width()
          , l = e.height()
          , c = t.width()
          , u = t.height()
          , d = (s - c) / 2 + a
          , h = 382 * (l - u) / 1e3 + r
          , m = t[0].style;
        m.left = Math.max(parseInt(d), a) + "px",
          m.top = Math.max(parseInt(h), r) + "px"
      },
      __follow: function(t) {
        var e, o, n, a, r, s, l, c, u, d, h, m, p, f, g, v, b, y, x, w, _, k, j, C, P, I, D, T, B, F, q = t.parentNode && i(t), S = this.__popup;
        return this.__followSkin && S.removeClass(this.__followSkin),
          q && (e = q.offset(),
          e.left * e.top < 0) ? this.__center() : (o = this,
            n = this.fixed,
            a = i(window),
            r = i(document),
            s = a.width(),
            l = a.height(),
            c = r.scrollLeft(),
            u = r.scrollTop(),
            d = S.width(),
            h = S.height(),
            m = q ? q.outerWidth() : 0,
            p = q ? q.outerHeight() : 0,
            f = this.__offset(t),
            g = f.left,
            v = f.top,
            b = n ? g - c : g,
            y = n ? v - u : v,
            x = n ? 0 : c,
            w = n ? 0 : u,
            _ = x + s - d,
            k = w + l - h,
            j = {},
            C = this.align.split(" "),
            P = this.className + "-",
            I = {
              top: "bottom",
              bottom: "top",
              left: "right",
              right: "left"
            },
            D = {
              top: "top",
              bottom: "top",
              left: "left",
              right: "left"
            },
            T = [{
              top: y - h,
              bottom: y + p,
              left: b - d,
              right: b + m
            }, {
              top: y,
              bottom: y - h + p,
              left: b,
              right: b - d + m
            }],
            B = {
              left: b + m / 2 - d / 2,
              top: y + p / 2 - h / 2
            },
            F = {
              left: [x, _],
              top: [w, k]
            },
            i.each(C, function(t, e) {
              T[t][e] > F[D[e]][1] && (e = C[t] = I[e]),
              T[t][e] < F[D[e]][0] && (C[t] = I[e])
            }),
          C[1] || (D[C[1]] = "left" === D[C[0]] ? "top" : "left",
            T[1][C[1]] = B[D[C[1]]]),
            P += C.join("-") + " " + this.className + "-follow",
            o.__followSkin = P,
          q && S.addClass(P),
            j[D[C[0]]] = parseInt(T[0][C[0]]),
            j[D[C[1]]] = parseInt(T[1][C[1]]),
            void S.css(j))
      },
      __offset: function(t) {
        var e, o, n, a, r, s, l, c, u, d = t.parentNode, h = d ? i(t).offset() : {
          left: t.pageX,
          top: t.pageY
        };
        return t = d ? t : t.target,
          e = t.ownerDocument,
          o = e.defaultView || e.parentWindow,
          o == window ? h : (n = o.frameElement,
            a = i(e),
            r = a.scrollLeft(),
            s = a.scrollTop(),
            l = i(n).offset(),
            c = l.left,
            u = l.top,
          {
            left: h.left + c - r,
            top: h.top + u - s
          })
      },
      __lock: function() {
        var t = this
          , o = this.__popup
          , n = this.__backdrop
          , r = {
          position: "fixed",
          left: 0,
          top: 0,
          width: "100%",
          height: "100%",
          overflow: "hidden",
          userSelect: "none",
          opacity: 0,
          background: this.backdropBackground
        };
        o.addClass(this.className + "-modal"),
          e.zIndex = e.zIndex + 2,
          this.__zIndex(),
        a || i.extend(r, {
          position: "absolute",
          width: i(window).width() + "px",
          height: i(document).height() + "px"
        }),
          n.css(r).animate({
            opacity: this.backdropOpacity
          }, 150).insertAfter(o).attr({
            tabindex: "0"
          }).on("focus", function() {
            t.focus()
          })
      },
      __unlock: function() {
        this.modal && (this.__popup.removeClass(this.className + "-modal"),
          this.__backdrop.remove(),
          delete this.modal)
      }
    }),
      e.zIndex = 1024,
      e.current = null ,
      e
  }),
  define("pub/js/ui/dialog/dialog-config", {
    backdropOpacity: .3,
    content: '<span class="ui-dialog-loading">Loading..</span>',
    title: "",
    statusbar: "",
    button: null ,
    ok: null ,
    cancel: null ,
    okValue: "ok",
    cancelValue: "cancel",
    cancelDisplay: !0,
    width: "",
    height: "",
    padding: "",
    type: "null",
    skin: "",
    quickClose: !1,
    innerHTML: '<div i="dialog" class="ui-dialog"><div class="ui-dialog-arrow-a"></div><div class="ui-dialog-arrow-b"></div><table class="ui-dialog-grid"><tr><td i="header" class="ui-dialog-header"><button i="close" class="ui-dialog-close">&#215;</button><div i="title" class="ui-dialog-title"></div></td></tr><tr><td i="body" class="ui-dialog-body"><div i="content" class="ui-dialog-content"></div></td></tr><tr><td i="footer" class="ui-dialog-footer"><div i="statusbar" class="ui-dialog-statusbar"></div><div i="button" class="ui-dialog-button"></div></td></tr></table></div>'
  }),
  define("pub/js/ui/dialog/dialog", ["require", "jquery", "./popup", "./dialog-config"], function(t) {
    var e, i, o, n, a, r, s, l, c, u = t("jquery"), d = t("./popup"), h = t("./dialog-config"), m = h.cssUri;
    return m && (e = t[t.toUrl ? "toUrl" : "resolve"],
    e && (m = e(m),
      m = '<link rel="stylesheet" href="' + m + '" />',
      u("base")[0] ? u("base").before(m) : u("head").append(m))),
      i = 0,
      o = new Date - 0,
      n = !("minWidth" in u("html")[0].style),
      a = "createTouch" in document && !("onmousemove" in document) || /(iPhone|iPad|iPod)/i.test(navigator.userAgent),
      r = !n && !a,
      s = function(t, e, n) {
        var l, c, d = t = t || {};
        return "string" != typeof t && 1 !== t.nodeType || (t = {
          content: t,
          fixed: !a
        }),
          t = u.extend(!0, {}, s.defaults, t),
          t._ = d,
          l = t.id = t.id || o + i,
          (c = s.get(l)) ? c.focus() : (r || (t.fixed = !1),
          t.quickClose && (t.modal = !0,
          d.backdropOpacity || (t.backdropOpacity = 0)),
          u.isArray(t.button) || (t.button = []),
          void 0 !== n && (t.cancel = n),
          t.cancel && t.button.push({
            id: "cancel",
            value: t.cancelValue,
            callback: t.cancel,
            display: t.cancelDisplay
          }),
          void 0 !== e && (t.ok = e),
          t.ok && t.button.push({
            id: "ok",
            value: t.okValue,
            callback: t.ok,
            autofocus: !0
          }),
            s.list[l] = new s.create(t))
      }
      ,
      l = function() {}
      ,
      l.prototype = d.prototype,
      c = s.prototype = new l,
      s.create = function(t) {
        var e, o = this;
        return u.extend(this, new d),
          e = u(this.node).html(t.innerHTML),
          this.options = t,
          this._popup = e,
          u.each(t, function(t, e) {
            "function" == typeof o[t] ? o[t](e) : o[t] = e
          }),
        t.zIndex && (d.zIndex = t.zIndex),
          e.attr({
            "aria-labelledby": this._$("title").attr("id", "title:" + this.id).attr("id"),
            "aria-describedby": this._$("content").attr("id", "content:" + this.id).attr("id")
          }),
          this._$("close").css("display", this.cancel === !1 ? "none" : "").attr("title", this.cancelValue).on("click", function(t) {
            o._trigger("cancel"),
              t.preventDefault()
          }),
          this._$("dialog").addClass(this.skin),
          this._$("body").css("padding", this.padding),
          e.on("click", "[data-id]", function(t) {
            var e = u(this);
            e.attr("disabled") || o._trigger(e.data("id")),
              t.preventDefault()
          }),
        t.quickClose && u(this.backdrop).on("onmousedown" in document ? "mousedown" : "click", function() {
          return o._trigger("cancel"),
            !1
        }),
          this._esc = function(t) {
            var e = t.target
              , i = e.nodeName
              , n = /^input|textarea$/i
              , a = d.current === o
              , r = t.keyCode;
            !a || n.test(i) && "button" !== e.type || 27 === r && o._trigger("cancel")
          }
          ,
          u(document).on("keydown", this._esc),
          this.addEventListener("remove", function() {
            u(document).off("keydown", this._esc),
              delete s.list[this.id]
          }),
          i++,
          s.oncreate(this),
          this
      }
      ,
      s.create.prototype = c,
      u.extend(c, {
        content: function(t) {
          var e = this._getTypeHtml(t);
          return e && (t = e),
            this._$("content").empty("")["object" == typeof t ? "append" : "html"](t),
            this.reset()
        },
        type: function(t) {
          this.options.type = t;
          var e = this._$("content").find(".ui-dialog-cnt-html");
          return e && 0 == e.length && (e = this._$("content")),
            this.content(e.html())
        },
        _getTypeHtml: function(t) {
          var e, i = this.options.type, o = "", n = "";
          if (this.options.url && this.options.url.length > 0)
            return !1;
          switch (i) {
            case "tips":
              n = "&#xe63b;";
              break;
            case "warning":
              n = "&#xe637;";
              break;
            case "question":
              n = "&#xe634;";
              break;
            case "error":
              n = "&#xe635;";
              break;
            case "ok":
              n = "&#xe636;";
              break;
            case "loading-text":
              n = '<img src="//s1.bbgstatic.com/pub/img/loading/loading32x32.gif" />'
          }
          return n.length > 0 ? (o = '<div class="ui-dialog-cnt-icon icon iconfont">' + n + '</div><div class="ui-dialog-cnt-html">' + t + "</div>",
            e = "ui-dialog-type ui-dialog-tips ui-dialog-warning ui-dialog-question ui-dialog-error ui-dialog-ok ui-dialog-loading ui-dialog-null",
            this._$("dialog").removeClass(e),
            this._$("dialog").addClass("ui-dialog-type ui-dialog-" + i),
            o) : !1
        },
        title: function(t) {
          return this._$("title").text(t),
            this._$("header")[t ? "show" : "hide"](),
            this
        },
        width: function(t) {
          return this._$("content").css("width", t),
            this.reset()
        },
        height: function(t) {
          return this._$("content").css("height", t),
            this.reset()
        },
        button: function(t) {
          var e, i, o;
          return t = t || [],
            e = this,
            i = "",
            o = 0,
            this.callbacks = {},
            "string" == typeof t ? i = t : u.each(t, function(t, n) {
              n.id = n.id || n.value,
                e.callbacks[n.id] = n.callback;
              var a = "";
              n.display === !1 ? a = ' style="display:none"' : o++,
                i += '<button type="button" data-id="' + n.id + '"' + a + (n.disabled ? " disabled" : "") + (n.autofocus ? ' autofocus class="ui-dialog-autofocus"' : "") + ">" + n.value + "</button>"
            }),
            this._$("footer")[o ? "show" : "hide"](),
            this._$("button").html(i),
            this
        },
        statusbar: function(t) {
          return this._$("statusbar").html(t)[t ? "show" : "hide"](),
            this
        },
        _$: function(t) {
          return this._popup.find("[i=" + t + "]")
        },
        _trigger: function(t) {
          var e = this.callbacks[t];
          return "function" != typeof e || e.call(this) !== !1 ? this.close().remove() : this
        }
      }),
      s.oncreate = u.noop,
      s.getCurrent = function() {
        return d.current
      }
      ,
      s.get = function(t) {
        return void 0 === t ? s.list : s.list[t]
      }
      ,
      s.list = {},
      s.defaults = h,
      s
  }),
  define("pub/js/ui/dialog/drag", ["require", "jquery"], function(t) {
    var e = t("jquery")
      , i = e(window)
      , o = e(document)
      , n = "createTouch" in document
      , a = document.documentElement
      , r = !("minWidth" in a.style)
      , s = !r && "onlosecapture" in a
      , l = "setCapture" in a
      , c = {
        start: n ? "touchstart" : "mousedown",
        over: n ? "touchmove" : "mousemove",
        end: n ? "touchend" : "mouseup"
      }
      , u = n ? function(t) {
        return t.touches || (t = t.originalEvent.touches.item(0)),
          t
      }
        : function(t) {
        return t
      }
      , d = function() {
        this.start = e.proxy(this.start, this),
          this.over = e.proxy(this.over, this),
          this.end = e.proxy(this.end, this),
          this.onstart = this.onover = this.onend = e.noop
      }
      ;
    return d.types = c,
      d.prototype = {
        start: function(t) {
          return t = this.startFix(t),
            o.on(c.over, this.over).on(c.end, this.end),
            this.onstart(t),
            !1
        },
        over: function(t) {
          return t = this.overFix(t),
            this.onover(t),
            !1
        },
        end: function(t) {
          return t = this.endFix(t),
            o.off(c.over, this.over).off(c.end, this.end),
            this.onend(t),
            !1
        },
        startFix: function(t) {
          return t = u(t),
            this.target = e(t.target),
            this.selectstart = function() {
              return !1
            }
            ,
            o.on("selectstart", this.selectstart).on("dblclick", this.end),
            s ? this.target.on("losecapture", this.end) : i.on("blur", this.end),
          l && this.target[0].setCapture(),
            t
        },
        overFix: function(t) {
          return t = u(t)
        },
        endFix: function(t) {
          return t = u(t),
            o.off("selectstart", this.selectstart).off("dblclick", this.end),
            s ? this.target.off("losecapture", this.end) : i.off("blur", this.end),
          l && this.target[0].releaseCapture(),
            t
        }
      },
      d.create = function(t, n) {
        var a, r, s, l, c = e(t), u = new d, h = d.types.start, m = function() {}
          , p = t.className.replace(/^\s|\s.*/g, "") + "-drag-start", f = {
          onstart: m,
          onover: m,
          onend: m,
          off: function() {
            c.off(h, u.start)
          }
        };
        return u.onstart = function(e) {
          var n, u, d, h = "fixed" === c.css("position"), m = o.scrollLeft(), g = o.scrollTop(), v = c.width(), b = c.height();
          a = 0,
            r = 0,
            s = h ? i.width() - v + a : o.width() - v,
            l = h ? i.height() - b + r : o.height() - b,
            n = c.offset(),
            u = this.startLeft = h ? n.left - m : n.left,
            d = this.startTop = h ? n.top - g : n.top,
            this.clientX = e.clientX,
            this.clientY = e.clientY,
            c.addClass(p),
            f.onstart.call(t, e, u, d)
        }
          ,
          u.onover = function(e) {
            var i = e.clientX - this.clientX + this.startLeft
              , o = e.clientY - this.clientY + this.startTop
              , n = c[0].style;
            i = Math.max(a, Math.min(s, i)),
              o = Math.max(r, Math.min(l, o)),
              n.left = i + "px",
              n.top = o + "px",
              f.onover.call(t, e, i, o)
          }
          ,
          u.onend = function(e) {
            var i = c.position()
              , o = i.left
              , n = i.top;
            c.removeClass(p),
              f.onend.call(t, e, o, n)
          }
          ,
          u.off = function() {
            c.off(h, u.start)
          }
          ,
          n ? u.start(n) : c.on(h, u.start),
          f
      }
      ,
      d
  }),
  define("pub/js/ui/dialog/dialog-plus", ["require", "jquery", "./dialog", "./drag"], function(t) {
    var e = t("jquery")
      , i = t("./dialog")
      , o = t("./drag");
    return i.oncreate = function(t) {
      var i, n, a, r = t.options, s = r._, l = r.url, c = r.oniframeload;
      if (l && (this.padding = r.padding = 0,
          i = e("<iframe />"),
          i.attr({
            id: "j" + t.id,
            src: l,
            name: t.id,
            width: "100%",
            height: "100%",
            allowtransparency: "yes",
            frameborder: "no",
            scrolling: "no"
          }).on("load", function() {
            var e;
            try {
              e = i[0].contentWindow.frameElement
            } catch (o) {}
            e && (r.width || t.width(i.contents().width()),
            r.height || t.height(i.contents().height())),
            c && c.call(t)
          }),
          t.addEventListener("beforeremove", function() {
            i.attr("src", "about:blank").remove()
          }, !1),
          t.content(i[0]),
          t.iframeNode = i[0]),
          !(s instanceof Object))
        for (n = function() {
          t.close().remove()
        }
               ,
               a = 0; a < frames.length; a++)
          try {
            if (s instanceof frames[a].Object) {
              e(frames[a]).one("unload", n);
              break
            }
          } catch (u) {}
      e(t.node).on(o.types.start, "[i=title]", function(e) {
        t.follow || (t.focus(),
          o.create(t.node, e))
      })
    }
      ,
      i.get = function(t) {
        var e, o, n, a;
        if (t && t.frameElement) {
          e = t.frameElement,
            o = i.list;
          for (a in o)
            if (n = o[a],
              n.node.getElementsByTagName("iframe")[0] === e)
              return n
        } else if (t)
          return i.list[t]
      }
      ,
      i
  }),
  define("bbg", ["require", "exports", "module", "jquery", "pub/js/core/bbg", "url", "pub/js/ui/dialog/dialog-plus"], function(t, e, i) {
    var o, n = t("jquery"), a = t("pub/js/core/bbg");
    a = t("url"),
      o = t("pub/js/ui/dialog/dialog-plus"),
      BBG.isEmptyObject = function(t) {
        for (var e in t)
          return !1;
        return !0
      }
      ,
      BBG.placeholder = function(t, e) {
        t.each(function(i, o) {
          var a = t.index(n(this))
            , r = 0 == n.trim(n(this).val()).length;
          !r && e.eq(a).hide()
        }).on("input propertychange", function() {
          var i = t.index(n(this))
            , o = 0 == n.trim(n(this).val()).length;
          e.eq(i)[o ? "fadeIn" : "fadeOut"]()
        })
      }
      ,
      BBG.confirm = function(t, e, i, a) {
        var r = {
          title: "提示",
          width: "300",
          content: '<p class="pop-tips">' + t + "</p>",
          okValue: "确定",
          ok: function() {
            e && e()
          },
          cancelValue: "取消",
          cancel: function() {
            i && i()
          }
        }
          , s = o(n.extend(r, a));
        s.showModal()
      }
      ,
      BBG.alert = function(t, e) {
        var i = o({
          title: "提示",
          width: "300",
          content: '<p class="pop-tips">' + t + "</p>",
          okValue: "确定",
          ok: function() {
            e && e()
          }
        });
        i.showModal()
      }
      ,
      BBG.waiting = function(t, e) {
        var i = o({
          content: '<p class="tips-div">' + (t || "loading...") + "</p>"
        });
        i.showModal(),
        e && e()
      }
      ,
      BBG.isHasSpChar = function(t) {
        var e = /[~#^$@%&!*'<>]/gi;
        return e.test(t)
      }
      ,
      BBG.pop = function(t) {
        var e = {
          title: "填写地址信息",
          id: "jPop",
          content: ""
        };
        o(n.extend(e, t)).showModal()
      }
      ,
      i.exports = BBG
  }),
  function(t, e, i) {
    "function" == typeof define && define.amd ? define("lib/core/1.0.0/io/messenger", i) : t[e] = i()
  }(this, "Messenger", function() {
    "use strict";
    function t(t, e) {
      var i = "";
      if (arguments.length < 2 ? i = "target and channelId are both requied" : "object" != typeof e ? i = "target must be window object" : "string" != typeof t && (i = "target channelId must be string type"),
          i)
        throw "TargetError: " + i;
      this._channelId = t,
        this.target = e
    }
    function e(t) {
      arguments.length > 1 && (t = arguments[1]);
      var t = t || "$";
      if (i[t])
        throw 'DuplicateError: a channel with id "' + t + '" is already exists.';
      i[t] = 1,
        this._attached = !1,
        this._targets = [],
        this.handleMessage = f(this.handleMessage, this),
        this._channelId = t,
        this._listeners = {}
    }
    var i, o = window, n = o.document, a = o.navigator, r = Object.prototype.hasOwnProperty, s = "postMessage" in o, l = "addEventListener" in n, c = o.JSON || 0, u = "__mc__", d = "<__JSON__>", h = function(t) {
        var e;
        if (!t || "object" != typeof t || t.nodeType || t.window === t)
          return !1;
        try {
          if (t.constructor && !r.call(t, "constructor") && !r.call(t.constructor.prototype, "isPrototypeOf"))
            return !1
        } catch (i) {
          return !1
        }
        for (e in t)
          ;
        return void 0 === e || r.call(t, e)
      }
      , m = c.stringify || function v(t) {
          var e, i, o, n, a = typeof t;
          if ("object" !== a || null  === t)
            return "string" === a && (t = '"' + t + '"'),
              String(t);
          o = [],
            n = t && t.constructor == Array;
          for (e in t)
            i = t[e],
              a = typeof i,
            t.hasOwnProperty(e) && ("string" === a ? i = '"' + i + '"' : "object" === a && null  !== i && (i = v(i)),
              o.push((n ? "" : '"' + e + '":') + String(i)));
          return (n ? "[" : "{") + String(o) + (n ? "]" : "}")
        }
      , p = c.parse || function(t) {
          return (0,
            o.eval)("(" + t + ")")
        }
      , f = function(t, e) {
        return t.bind ? t.bind(e) : function() {
          t.apply(e, arguments)
        }
      }
      , g = function(t, e) {
        for (var i in e)
          e.hasOwnProperty(i) && (t[i] = e[i]);
        return t
      }
      ;
    return t.prototype.send = s ? function(t) {
      this.target.postMessage(this._channelId + t, "*")
    }
      : function(t) {
      var e = this._channelId
        , i = a[e];
      if ("function" != typeof i)
        throw 'Target (channel="' + e + '") callback function is not defined';
      try {
        i(e + t, o)
      } catch (n) {}
    }
      ,
      i = {},
      g(e.prototype, {
        _mId: 0,
        _initMessenger: function() {
          var t, e = this;
          e._attached || (e._attached = !0,
            t = e.handleMessage,
            s ? l ? o.addEventListener("message", t, !1) : o.attachEvent("onmessage", t) : a[e._channelId] = t)
        },
        addTarget: function(e) {
          for (var i = this._targets, o = i.length; o--; )
            if (i[o][0] === e)
              return this;
          return i.push([e, new t(this._channelId,e)]),
            this
        },
        removeTarget: function(t) {
          for (var e = this._targets, i = e.length; i--; )
            e[i][0] === t && e.splice(i, 1);
          return this
        },
        handleMessage: function(t) {
          var e, i, o, n, a, r, s = this;
          if (t && (e = t,
              r = this._channelId,
            "object" == typeof t && (e = t.data),
            0 === e.indexOf(r))) {
            if (e = e.substring(r.length),
              0 === e.indexOf(d))
              try {
                o = p(e.substring(d.length))
              } catch (t) {
                setTimeout(function() {
                  console.error(t, e)
                }, 1)
              }
            else
              o = {
                type: "*",
                data: e
              };
            (i = o && o.type) && (n = t.source,
              a = {
                target: t.target,
                source: n,
                send: function(t) {
                  s.addTarget(n),
                    s.send(u + r + "_" + o.mId, t),
                    s.removeTarget(n)
                }
              },
              s.emit(i, o.data, a))
          }
        },
        listen: function(t, e) {
          return void 0 === e && (e = t,
            t = "*"),
            this.on(t, e),
            this
        },
        clear: function() {
          return this._listeners = {},
            this
        },
        sendMessage: function(t, e, i) {
          var o, n, a, r, s;
          if ("function" == typeof e && (i = e,
              e = void 0),
            "object" == typeof e && !h(e))
            throw "The CORSS message data not a valid plain object.";
          for (o = ++this._mId,
                 n = this._channelId,
                 a = this._targets,
                 r = {
                   type: t,
                   data: e,
                   mId: o
                 },
               "function" == typeof i && this.on(u + n + "_" + o, function(e) {
                 i({
                   type: t,
                   data: e,
                   mId: o
                 })
               }),
                 e = d + m(r),
                 s = a.length; s--; )
            a[s][1].send(e);
          return this
        },
        send: function(t, e, i) {
          return void 0 === e && (e = t,
            t = "*"),
            this.sendMessage(t, e, i)
        },
        on: function(t, e) {
          return this._initMessenger(),
          this._listeners[t] || (this._listeners[t] = []),
          "function" == typeof e && this._listeners[t].push(e),
            this
        },
        emit: function(t) {
          var e, i, o, n = this._listeners[t];
          if (n)
            for (e = 0,
                   i = n.length,
                   o = Array.prototype.slice.call(arguments, 1); i > e; e++)
              n[e].apply(this, o);
          return this
        },
        destroy: function() {
          if (s) {
            var t = this.handleMessage;
            l ? o.removeEventListener("message", t) : o.detachEvent("onmessage", t)
          } else
            delete a[this._channelId];
          this.clear(),
            this._targets.length = 0,
            delete i[this._channelId]
        }
      }),
      e
  }),
  define("lib/gallery/ssologin/1.0.0/ssologin", ["require", "exports", "module", "lib/ui/box/1.0.1/box", "lib/core/1.0.0/io/messenger", "./channel"], function(t, e, i) {
    "use strict";
    var o, n = t("lib/ui/box/1.0.1/box"), a = t("lib/core/1.0.0/io/messenger"), r = function(t, e) {
        for (var i in e)
          e.hasOwnProperty(i) && (t[i] = e[i]);
        return t
      }
      , s = t("./channel"), l = "yunhou.com", c = "https://ssl.yunhou.com/passport/loginMini", u = function(t, e, i, u) {
        var d, h;
        "function" == typeof t && (u = i,
          i = e,
          e = t,
          t = null ),
        o && o.destroy(),
          t = r({
            url: c,
            width: 380,
            height: 440,
            fixed: !0,
            scrolling: "no",
            close: !1
          }, t),
          d = t.domain || l;
        try {
          document.domain = d
        } catch (m) {
          setTimeout(function() {
            console.warn(m)
          }, 1)
        }
        return o = n.loadUrl(t.url, t),
        u && o.on("hide", function() {
          u()
        }),
          h = new a("channel/passport"),
          h.on("resizeBox", function(t) {
            o.height(t.height)
          }),
          h.on("loginCallback", function(t) {
            s.fire("login", t),
            e && e(t),
              o.hide()
          }),
          h.on("loginErrorCallback", function(t) {
            s.fire("error", t),
            i && i(t)
          }),
          o.on("destroy", function(t) {
            o = null ,
              h.destroy()
          }),
          h.on("loginHide", function(t) {
            o.hide()
          }),
          o
      }
      ;
    i.exports = {
      showDialog: u
    }
  }),
  define("lib/ui/dialog/6.0.2/popup", ["require", "jquery"], function(t) {
    function e() {
      this.destroyed = !1,
        this.__popup = i("<div />").attr({
          tabindex: "-1"
        }).css({
          display: "none",
          position: "absolute",
          outline: 0
        }).html(this.innerHTML).appendTo("body"),
        this.__backdrop = i("<div />"),
        this.node = this.__popup[0],
        this.backdrop = this.__backdrop[0],
        o++
    }
    var i = t("jquery")
      , o = 0
      , n = !("minWidth" in i("html")[0].style)
      , a = !n;
    return i.extend(e.prototype, {
      node: null ,
      backdrop: null ,
      fixed: !1,
      destroyed: !0,
      open: !1,
      returnValue: "",
      autofocus: !0,
      align: "bottom left",
      backdropBackground: "#000",
      backdropOpacity: .7,
      innerHTML: "",
      className: "ui-popup",
      autoTime: 3e3,
      show: function(t) {
        var e, o;
        return "number" == typeof arguments[0] && (t = void 0),
          this.destroyed ? this : (e = this,
            o = this.__popup,
            this.__activeElement = this.__getActive(),
            this.open = !0,
            this.follow = t && i(t)[0] || this.follow,
          this.__ready || (o.addClass(this.className),
          this.modal && this.__lock(),
          o.html() || o.html(this.innerHTML),
          n || i(window).on("resize", this.__onresize = function() {
              e.reset()
            }
          ),
            this.__ready = !0),
            o.addClass(this.className + "-show").attr("role", this.modal ? "alertdialog" : "dialog").css("position", this.fixed ? "fixed" : "absolute").show(),
            this.__backdrop.show(),
            this.reset().focus(),
            this.__dispatchEvent("show"),
            this)
      },
      showModal: function() {
        return this.modal = !0,
          this.show.apply(this, arguments)
      },
      time: function() {
        var t, e, i = this.autoTime;
        "number" == typeof arguments[0] ? i = arguments[0] : "number" == typeof arguments[1] && (i = arguments[1]),
          t = this.show.apply(this, arguments),
        i && (e = this,
          function(t) {
            setTimeout(function() {
              t.remove()
            }, i)
          }(e))
      },
      close: function(t) {
        return !this.destroyed && this.open && (void 0 !== t && (this.returnValue = t),
          this.__popup.hide().removeClass(this.className + "-show"),
          this.__backdrop.hide(),
          this.open = !1,
          this.blur(),
          this.__dispatchEvent("close")),
          this
      },
      remove: function() {
        if (this.destroyed)
          return this;
        this.__dispatchEvent("beforeremove"),
        e.current === this && (e.current = null ),
          this.__unlock(),
          this.__popup.remove(),
          this.__backdrop.remove(),
        n || i(window).off("resize", this.__onresize),
          this.__dispatchEvent("remove");
        for (var t in this)
          delete this[t];
        return this
      },
      reset: function() {
        var t = this.follow;
        return t ? this.__follow(t) : this.__center(),
          this.__dispatchEvent("reset"),
          this
      },
      focus: function() {
        var t, o = this.node, n = e.current;
        return n && n !== this && n.blur(!1),
        i.contains(o, this.__getActive()) || (t = this.__popup.find("[autofocus]")[0],
          !this._autofocus && t ? this._autofocus = !0 : t = o,
          this.__focus(t)),
          e.current = this,
          this.__popup.addClass(this.className + "-focus"),
          this.__zIndex(),
          this.__dispatchEvent("focus"),
          this
      },
      blur: function() {
        var t = this.__activeElement
          , e = arguments[0];
        return e !== !1 && this.__focus(t),
          this._autofocus = !1,
          this.__popup.removeClass(this.className + "-focus"),
          this.__dispatchEvent("blur"),
          this
      },
      addEventListener: function(t, e) {
        return this.__getEventListener(t).push(e),
          this
      },
      removeEventListener: function(t, e) {
        var i, o = this.__getEventListener(t);
        for (i = 0; i < o.length; i++)
          e === o[i] && o.splice(i--, 1);
        return this
      },
      __getEventListener: function(t) {
        var e = this.__listener;
        return e || (e = this.__listener = {}),
        e[t] || (e[t] = []),
          e[t]
      },
      __dispatchEvent: function(t) {
        var e, i = this.__getEventListener(t), o = Array.prototype.slice.call(arguments, 1);
        for (this["on" + t] && this["on" + t].apply(this, o),
               e = 0; e < i.length; e++)
          i[e].apply(this, o)
      },
      __focus: function(t) {
        try {
          this.autofocus && !/^iframe$/i.test(t.nodeName) && t.focus()
        } catch (e) {}
      },
      __getActive: function() {
        var t, e, i;
        try {
          return t = document.activeElement,
            e = t.contentDocument,
            i = e && e.activeElement || t
        } catch (o) {}
      },
      __zIndex: function() {
        var t = e.zIndex++;
        this.__popup.css("zIndex", t),
          this.__backdrop.css("zIndex", t - 1),
          this.zIndex = t
      },
      __center: function() {
        var t = this.__popup
          , e = i(window)
          , o = i(document)
          , n = this.fixed
          , a = n ? 0 : o.scrollLeft()
          , r = n ? 0 : o.scrollTop()
          , s = e.width()
          , l = e.height()
          , c = t.width()
          , u = t.height()
          , d = (s - c) / 2 + a
          , h = 382 * (l - u) / 1e3 + r
          , m = t[0].style;
        m.left = Math.max(parseInt(d), a) + "px",
          m.top = Math.max(parseInt(h), r) + "px"
      },
      __follow: function(t) {
        var e, o, n, a, r, s, l, c, u, d, h, m, p, f, g, v, b, y, x, w, _, k, j, C, P, I, D, T, B, F, q = t.parentNode && i(t), S = this.__popup;
        return this.__followSkin && S.removeClass(this.__followSkin),
          q && (e = q.offset(),
          e.left * e.top < 0) ? this.__center() : (o = this,
            n = this.fixed,
            a = i(window),
            r = i(document),
            s = a.width(),
            l = a.height(),
            c = r.scrollLeft(),
            u = r.scrollTop(),
            d = S.width(),
            h = S.height(),
            m = q ? q.outerWidth() : 0,
            p = q ? q.outerHeight() : 0,
            f = this.__offset(t),
            g = f.left,
            v = f.top,
            b = n ? g - c : g,
            y = n ? v - u : v,
            x = n ? 0 : c,
            w = n ? 0 : u,
            _ = x + s - d,
            k = w + l - h,
            j = {},
            C = this.align.split(" "),
            P = this.className + "-",
            I = {
              top: "bottom",
              bottom: "top",
              left: "right",
              right: "left"
            },
            D = {
              top: "top",
              bottom: "top",
              left: "left",
              right: "left"
            },
            T = [{
              top: y - h,
              bottom: y + p,
              left: b - d,
              right: b + m
            }, {
              top: y,
              bottom: y - h + p,
              left: b,
              right: b - d + m
            }],
            B = {
              left: b + m / 2 - d / 2,
              top: y + p / 2 - h / 2
            },
            F = {
              left: [x, _],
              top: [w, k]
            },
            i.each(C, function(t, e) {
              T[t][e] > F[D[e]][1] && (e = C[t] = I[e]),
              T[t][e] < F[D[e]][0] && (C[t] = I[e])
            }),
          C[1] || (D[C[1]] = "left" === D[C[0]] ? "top" : "left",
            T[1][C[1]] = B[D[C[1]]]),
            P += C.join("-") + " " + this.className + "-follow",
            o.__followSkin = P,
          q && S.addClass(P),
            j[D[C[0]]] = parseInt(T[0][C[0]]),
            j[D[C[1]]] = parseInt(T[1][C[1]]),
            void S.css(j))
      },
      __offset: function(t) {
        var e, o, n, a, r, s, l, c, u, d = t.parentNode, h = d ? i(t).offset() : {
          left: t.pageX,
          top: t.pageY
        };
        return t = d ? t : t.target,
          e = t.ownerDocument,
          o = e.defaultView || e.parentWindow,
          o == window ? h : (n = o.frameElement,
            a = i(e),
            r = a.scrollLeft(),
            s = a.scrollTop(),
            l = i(n).offset(),
            c = l.left,
            u = l.top,
          {
            left: h.left + c - r,
            top: h.top + u - s
          })
      },
      __lock: function() {
        var t = this
          , o = this.__popup
          , n = this.__backdrop
          , r = {
          position: "fixed",
          left: 0,
          top: 0,
          width: "100%",
          height: "100%",
          overflow: "hidden",
          userSelect: "none",
          opacity: 0,
          background: this.backdropBackground
        };
        o.addClass(this.className + "-modal"),
          e.zIndex = e.zIndex + 2,
          this.__zIndex(),
        a || i.extend(r, {
          position: "absolute",
          width: i(window).width() + "px",
          height: i(document).height() + "px"
        }),
          n.css(r).animate({
            opacity: this.backdropOpacity
          }, 150).insertAfter(o).attr({
            tabindex: "0"
          }).on("focus", function() {
            t.focus()
          })
      },
      __unlock: function() {
        this.modal && (this.__popup.removeClass(this.className + "-modal"),
          this.__backdrop.remove(),
          delete this.modal)
      }
    }),
      e.prototype.on = e.prototype.addEventListener,
      e.prototype.un = e.prototype.removeEventListener,
      e.prototype.emit = e.prototype.__dispatchEvent,
      e.zIndex = 1024,
      e.current = null ,
      e
  }),
  define("lib/ui/dialog/6.0.2/dialog-config", {
    backdropOpacity: .3,
    content: '<span class="ui-dialog-loading">Loading..</span>',
    title: "",
    statusbar: "",
    button: null ,
    ok: null ,
    cancel: null ,
    okValue: "ok",
    cancelValue: "cancel",
    cancelDisplay: !0,
    width: "",
    height: "",
    padding: "",
    type: "null",
    skin: "",
    quickClose: !1,
    cssUri: "./dialog.css",
    innerHTML: '<div i="dialog" class="ui-dialog"><div class="ui-dialog-arrow-a"></div><div class="ui-dialog-arrow-b"></div><table class="ui-dialog-grid"><tr><td i="header" class="ui-dialog-header"><button i="close" class="ui-dialog-close">&#215;</button><div i="title" class="ui-dialog-title"></div></td></tr><tr><td i="body" class="ui-dialog-body"><div i="content" class="ui-dialog-content"></div></td></tr><tr><td i="footer" class="ui-dialog-footer"><div i="statusbar" class="ui-dialog-statusbar"></div><div i="button" class="ui-dialog-button"></div></td></tr></table></div>'
  }),
  define("lib/ui/dialog/6.0.2/dialog", ["require", "jquery", "./popup", "./dialog-config"], function(t) {
    var e, i, o, n, a, r, s, l, c, u = t("jquery"), d = t("./popup"), h = t("./dialog-config"), m = h.cssUri;
    return m && (e = t[t.toUrl ? "toUrl" : "resolve"],
    e && (m = e(m),
      m = '<link rel="stylesheet" href="' + m + '" />',
      u("base")[0] ? u("base").before(m) : u("head").append(m))),
      i = 0,
      o = new Date - 0,
      n = !("minWidth" in u("html")[0].style),
      a = "createTouch" in document && !("onmousemove" in document) || /(iPhone|iPad|iPod)/i.test(navigator.userAgent),
      r = !n && !a,
      s = function(t, e, n) {
        var l, c, d = t = t || {};
        return "string" != typeof t && 1 !== t.nodeType || (t = {
          content: t,
          fixed: !a
        }),
          t = u.extend(!0, {}, s.defaults, t),
          t._ = d,
          l = t.id = t.id || o + i,
          (c = s.get(l)) ? c.focus() : (r || (t.fixed = !1),
          t.quickClose && (t.modal = !0,
          d.backdropOpacity || (t.backdropOpacity = 0)),
          u.isArray(t.button) || (t.button = []),
          void 0 !== n && (t.cancel = n),
          t.cancel && t.button.push({
            id: "cancel",
            value: t.cancelValue,
            callback: t.cancel,
            display: t.cancelDisplay
          }),
          void 0 !== e && (t.ok = e),
          t.ok && t.button.push({
            id: "ok",
            value: t.okValue,
            callback: t.ok,
            autofocus: !0
          }),
            s.list[l] = new s.create(t))
      }
      ,
      l = function() {}
      ,
      l.prototype = d.prototype,
      c = s.prototype = new l,
      s.create = function(t) {
        var e, o = this;
        return u.extend(this, new d),
          e = u(this.node).html(t.innerHTML),
          this.options = t,
          this._popup = e,
          u.each(t, function(t, e) {
            "function" == typeof o[t] ? o[t](e) : o[t] = e
          }),
        t.zIndex && (d.zIndex = t.zIndex),
          e.attr({
            "aria-labelledby": this._$("title").attr("id", "title:" + this.id).attr("id"),
            "aria-describedby": this._$("content").attr("id", "content:" + this.id).attr("id")
          }),
          this._$("close").css("display", this.cancel === !1 ? "none" : "").attr("title", this.cancelValue).on("click", function(t) {
            o._trigger("cancel"),
              t.preventDefault()
          }),
          this._$("dialog").addClass(this.skin),
          this._$("body").css("padding", this.padding),
          e.on("click", "[data-id]", function(t) {
            t.preventDefault();
            var e = u(this);
            return e.attr("disabled") ? void 0 : o._trigger(e.data("id"))
          }),
        t.quickClose && u(this.backdrop).on("onmousedown" in document ? "mousedown" : "click", function() {
          return o._trigger("cancel"),
            !1
        }),
          this._esc = function(t) {
            var e = t.target
              , i = e.nodeName
              , n = /^input|textarea$/i
              , a = d.current === o
              , r = t.keyCode;
            !a || n.test(i) && "button" !== e.type || 27 === r && o._trigger("cancel")
          }
          ,
          u(document).on("keydown", this._esc),
          this.addEventListener("remove", function() {
            u(document).off("keydown", this._esc),
              delete s.list[this.id]
          }),
          i++,
          s.oncreate(this),
          this
      }
      ,
      s.create.prototype = c,
      u.extend(c, {
        content: function(t) {
          var e = this._getTypeHtml(t);
          return e && (t = e),
            this._$("content").empty("")["object" == typeof t ? "append" : "html"](t),
            this.reset()
        },
        type: function(t) {
          this.options.type = t;
          var e = this._$("content").find(".ui-dialog-cnt-html");
          return e && 0 == e.length && (e = this._$("content")),
            this.content(e.html())
        },
        _getTypeHtml: function(t) {
          var e, i = this.options.type, o = "", n = "";
          if (this.options.url && this.options.url.length > 0)
            return !1;
          switch (i) {
            case "tips":
              n = "&#xe63b;";
              break;
            case "warn":
              n = "&#xe637;";
              break;
            case "question":
              n = "&#xe634;";
              break;
            case "error":
              n = "&#xe635;";
              break;
            case "ok":
              n = "&#xe636;";
              break;
            case "loading-text":
              n = '<img src="//static5.bubugao.com/public/img/loading/loading32x32.gif" />'
          }
          return n.length > 0 ? (o = '<div class="ui-dialog-cnt-icon icon iconfont">' + n + '</div><div class="ui-dialog-cnt-html">' + t + "</div>",
            e = "ui-dialog-type ui-dialog-tips ui-dialog-warn ui-dialog-question ui-dialog-error ui-dialog-ok ui-dialog-loading ui-dialog-null",
            this._$("dialog").removeClass(e),
            this._$("dialog").addClass("ui-dialog-type ui-dialog-" + i),
            o) : !1
        },
        title: function(t) {
          return this._$("title").text(t),
            this._$("header")[t ? "show" : "hide"](),
            this
        },
        width: function(t) {
          return this._$("content").css("width", t),
            this.reset()
        },
        height: function(t) {
          return this._$("content").css("height", t),
            this.reset()
        },
        button: function(t) {
          var e, i, o;
          return t = t || [],
            e = this,
            i = "",
            o = 0,
            this.callbacks = {},
            "string" == typeof t ? i = t : u.each(t, function(t, n) {
              n.id = n.id || n.value,
                e.callbacks[n.id] = n.callback;
              var a = "";
              n.display === !1 ? a = ' style="display:none"' : o++,
                i += '<button type="button" data-id="' + n.id + '"' + a + (n.disabled ? " disabled" : "") + (n.autofocus ? ' autofocus class="ui-dialog-autofocus"' : "") + ">" + n.value + "</button>"
            }),
            this._$("footer")[o ? "show" : "hide"](),
            this._$("button").html(i),
            this
        },
        statusbar: function(t) {
          return this._$("statusbar").html(t)[t ? "show" : "hide"](),
            this
        },
        _$: function(t) {
          return this._popup.find("[i=" + t + "]")
        },
        _trigger: function(t) {
          var e = this.callbacks[t];
          return "function" != typeof e || e.call(this) !== !1 ? this.close().remove() : this
        }
      }),
      s.oncreate = u.noop,
      s.getCurrent = function() {
        return d.current
      }
      ,
      s.get = function(t) {
        return void 0 === t ? s.list : s.list[t]
      }
      ,
      s.list = {},
      s.defaults = h,
      s
  }),
  define("lib/ui/dialog/6.0.2/drag", ["require", "jquery"], function(t) {
    var e = t("jquery")
      , i = e(window)
      , o = e(document)
      , n = "createTouch" in document
      , a = document.documentElement
      , r = !("minWidth" in a.style)
      , s = !r && "onlosecapture" in a
      , l = "setCapture" in a
      , c = {
        start: n ? "touchstart" : "mousedown",
        over: n ? "touchmove" : "mousemove",
        end: n ? "touchend" : "mouseup"
      }
      , u = n ? function(t) {
        return t.touches || (t = t.originalEvent.touches.item(0)),
          t
      }
        : function(t) {
        return t
      }
      , d = function() {
        this.start = e.proxy(this.start, this),
          this.over = e.proxy(this.over, this),
          this.end = e.proxy(this.end, this),
          this.onstart = this.onover = this.onend = e.noop
      }
      ;
    return d.types = c,
      d.prototype = {
        start: function(t) {
          return t = this.startFix(t),
            o.on(c.over, this.over).on(c.end, this.end),
            this.onstart(t),
            !1
        },
        over: function(t) {
          return t = this.overFix(t),
            this.onover(t),
            !1
        },
        end: function(t) {
          return t = this.endFix(t),
            o.off(c.over, this.over).off(c.end, this.end),
            this.onend(t),
            !1
        },
        startFix: function(t) {
          return t = u(t),
            this.target = e(t.target),
            this.selectstart = function() {
              return !1
            }
            ,
            o.on("selectstart", this.selectstart).on("dblclick", this.end),
            s ? this.target.on("losecapture", this.end) : i.on("blur", this.end),
          l && this.target[0].setCapture(),
            t
        },
        overFix: function(t) {
          return t = u(t)
        },
        endFix: function(t) {
          return t = u(t),
            o.off("selectstart", this.selectstart).off("dblclick", this.end),
            s ? this.target.off("losecapture", this.end) : i.off("blur", this.end),
          l && this.target[0].releaseCapture(),
            t
        }
      },
      d.create = function(t, n) {
        var a, r, s, l, c = e(t), u = new d, h = d.types.start, m = function() {}
          , p = t.className.replace(/^\s|\s.*/g, "") + "-drag-start", f = {
          onstart: m,
          onover: m,
          onend: m,
          off: function() {
            c.off(h, u.start)
          }
        };
        return u.onstart = function(e) {
          var n, u, d, h = "fixed" === c.css("position"), m = o.scrollLeft(), g = o.scrollTop(), v = c.width(), b = c.height();
          a = 0,
            r = 0,
            s = h ? i.width() - v + a : o.width() - v,
            l = h ? i.height() - b + r : o.height() - b,
            n = c.offset(),
            u = this.startLeft = h ? n.left - m : n.left,
            d = this.startTop = h ? n.top - g : n.top,
            this.clientX = e.clientX,
            this.clientY = e.clientY,
            c.addClass(p),
            f.onstart.call(t, e, u, d)
        }
          ,
          u.onover = function(e) {
            var i = e.clientX - this.clientX + this.startLeft
              , o = e.clientY - this.clientY + this.startTop
              , n = c[0].style;
            i = Math.max(a, Math.min(s, i)),
              o = Math.max(r, Math.min(l, o)),
              n.left = i + "px",
              n.top = o + "px",
              f.onover.call(t, e, i, o)
          }
          ,
          u.onend = function(e) {
            var i = c.position()
              , o = i.left
              , n = i.top;
            c.removeClass(p),
              f.onend.call(t, e, o, n)
          }
          ,
          u.off = function() {
            c.off(h, u.start)
          }
          ,
          n ? u.start(n) : c.on(h, u.start),
          f
      }
      ,
      d
  }),
  define("lib/ui/dialog/6.0.2/dialog-plus", ["require", "jquery", "./dialog", "./drag"], function(t) {
    var e = t("jquery")
      , i = t("./dialog")
      , o = t("./drag");
    return i.oncreate = function(t) {
      var i, n, a, r = t.options, s = r._, l = r.url, c = r.oniframeload;
      if (l && (this.padding = r.padding = 0,
          i = e("<iframe />"),
          i.attr({
            id: "j" + t.id,
            src: l,
            name: t.id,
            width: "100%",
            height: "100%",
            allowtransparency: "yes",
            frameborder: "no",
            scrolling: "no"
          }).on("load", function() {
            var o, n;
            try {
              n = i[0].contentWindow,
                o = e(n.document)
            } catch (a) {}
            o && o.length && (r.width || t.width(o.width()),
            r.height || t.height(o.height())),
            c && c.call(t),
              t.emit("load", n)
          }),
          t.addEventListener("beforeremove", function() {
            i.attr("src", "about:blank").remove()
          }, !1),
          t.content(i[0]),
          t.iframeNode = i[0]),
          !(s instanceof Object))
        for (n = function() {
          t.close().remove()
        }
               ,
               a = 0; a < frames.length; a++)
          try {
            if (s instanceof frames[a].Object) {
              e(frames[a]).one("unload", n);
              break
            }
          } catch (u) {}
      e(t.node).on(o.types.start, "[i=title]", function(e) {
        t.follow || (t.focus(),
          o.create(t.node, e))
      })
    }
      ,
      i.get = function(t) {
        var e, o, n, a;
        if (t && t.frameElement) {
          e = t.frameElement,
            o = i.list;
          for (a in o)
            if (n = o[a],
              n.node.getElementsByTagName("iframe")[0] === e)
              return n
        } else if (t)
          return i.list[t]
      }
      ,
      i
  }),
  define("lib/ui/box/1.0.0/base", ["require", "exports", "module", "../../dialog/6.0.2/dialog-plus", "../../dialog/6.0.2/popup"], function(t, e, i) {
    "use strict";
    var o, n, a = function() {}
      , r = Array.prototype.slice, s = function(t, e) {
      for (var i in e)
        e.hasOwnProperty(i) && (t[i] = e[i]);
      return t
    }
      , l = Array.isArray || function(t) {
        return t && t instanceof Array
      }
      , c = t("../../dialog/6.0.2/dialog-plus"), u = t("../../dialog/6.0.2/popup").prototype, d = function(t) {
      var e, i;
      if ("string" == typeof t && (t = {
          content: t
        }),
          e = t.button,
        "object" == typeof e && !l(e)) {
        t.button = [];
        for (i in e)
          e.hasOwnProperty(i) && t.button.push(s({
            id: i
          }, e[i]))
      }
      return c(t)
    }
      , h = u.show;
    u.show = function() {
      var t = r.call(arguments, 0);
      return "boolean" == typeof t[0] && (this.modal = t[0],
        t.shift()),
        h.apply(this, t)
    }
      ,
      u.hide = function(t) {
        return t ? this.remove() : this.close()
      }
      ,
      u.destroy = function() {
        this.remove()
      }
      ,
      o = 2e3,
      n = {
        create: d,
        alert: function(t, e) {
          e = e || a;
          var i = d({
            title: "提示",
            width: "300",
            content: '<p class="pop-tips">' + t + "</p>",
            okValue: "确定",
            ok: function() {
              e()
            }
          });
          return i.show(!0)
        },
        confirm: function(t, e, i, o) {
          var n, a;
          if ("function" != typeof e)
            throw "Illegal argument, the callback cannot be null.";
          return "function" != typeof i && (i = e),
            n = function(t) {
              t ? e(t) : i(t)
            }
            ,
            a = d({
              id: "_dialogConfirm",
              type: "question",
              content: t,
              align: "top right",
              okValue: "确定",
              cancelValue: "取消",
              ok: function() {
                n(!0)
              },
              cancel: function() {
                n(!1)
              }
            }),
            a.show(!0, o)
        },
        error: function(t, e, i) {
          return d({
            id: "_dialogError",
            type: "error",
            align: "top",
            content: t
          }).time(e, i || o)
        },
        warn: function(t, e, i) {
          return d({
            id: "_dialogWarning",
            type: "warn",
            align: "top",
            content: t
          }).time(e, i || o)
        },
        ok: function(t, e, i) {
          return d({
            id: "_dialogOk",
            type: "ok",
            align: "top",
            content: t
          }).time(e, i || o)
        },
        tips: function(t, e, i) {
          return d({
            id: "_dialogTips",
            type: "tips",
            align: "top",
            content: t
          }).time(e, i || o)
        },
        loading: function(t, e) {
          var i, o;
          return e = e || {},
            i = e.isModal,
            o = d({
              id: "_dialogForLoading",
              type: "loading-text",
              content: t
            }),
            o.show(void 0 === i ? !0 : !!i)
        }
      },
      i.exports = n
  }),
  define("module/item/1.0.0/common/common", ["require", "exports", "module", "lib/gallery/ssologin/1.0.0/ssologin", "lib/core/1.0.0/io/cookie", "lib/core/1.0.0/io/request", "lib/ui/box/1.0.0/base", "lib/core/1.0.0/dom/delegator", "lib/core/1.0.0/event/emitter", "lib/plugins/lazyload/1.9.3/lazyload"], function(require, exports, module) {
    "use strict";
    var ssologin = require("lib/gallery/ssologin/1.0.0/ssologin")
      , cookie = require("lib/core/1.0.0/io/cookie")
      , io = require("lib/core/1.0.0/io/request")
      , dialog = require("lib/ui/box/1.0.0/base")
      , Delegator = require("lib/core/1.0.0/dom/delegator")
      , Emitter = require("lib/core/1.0.0/event/emitter")
      , Lazyload = require("lib/plugins/lazyload/1.9.3/lazyload")
      , com = {
      opt: {
        moduleId: "jGoodsItem",
        Delegator: Delegator,
        dialog: dialog,
        cookie: cookie,
        Emitter: Emitter
      },
      init: function() {
        var t = this;
        $.extend(this, this.opt),
          t.o = $("#" + t.moduleId),
          t.delegator = Delegator("#" + t.moduleId),
          t.gIsMarketable = t.boot(t.getCacheData("is-marketable")),
          t.gProductId = t.getCacheData("product-id"),
          t.gGoodsId = t.getCacheData("goods-id"),
          t.gShopId = t.getCacheData("shop-id"),
          t.isGlobalShopFlag = t.boot(t.getCacheData("isGlobalShopFlag"))
      },
      getHash: function() {
        var t = window.location.hash ? window.location.hash : !1;
        return t ? t.replace("#", "") : !1
      },
      resetImageLoader: function(t) {
        new Lazyload("img.jImg",{
          effect: "fadeIn",
          load: function(t) {
            $(t).removeClass("img-error").removeAttr("data-src")
          },
          container: t
        })
      },
      getSizeByUri: function(t) {
        var e = {}
          , i = /_([0-9]+)x([0-9]+).([^.\/_]+)\!([0-9]+)$/
          , o = i.exec(t);
        return o ? (e.w = parseInt(o[1]),
          e.h = parseInt(o[2]),
          e.size = parseInt(o[4])) : (i = /_([0-9]+)x([0-9]+).([^.\/_]+)$/,
          o = i.exec(t),
          o ? (e.w = parseInt(o[1]),
            e.h = parseInt(o[2]),
            e.size = e.w) : e = null ),
          e
      },
      boot: function(t) {
        return /^true$/i.test(t)
      },
      loginBox: function(t) {
        var e = cookie("_nick");
        return e ? t && t() : ssologin.showDialog(function(e) {
          t && t(e)
        }, function(t) {}),
          e
      },
      ajax: function(t, e, i, o, n) {
        var a = this
          , r = {
          product_id: a.gProductId,
          isGlobal: a.isGlobalShopFlag
        };
        io.jsonp(t, $.extend(r, e), function(t) {
          i && i(t)
        }, function(t) {
          o && o(t),
          t && t._error && t._error.msg && 0 != t._error.msg.length && dialog.error(t._error.msg, n)
        }, n).on("error", function(t) {})
      },
      getCacheData: function(t) {
        return $("#jCacheData").attr("data-" + t)
      },
      setCacheData: function(t, e) {
        $("#jCacheData").attr("data-" + t, e)
      },
      getCacheJson: function(key) {
        var val = $("#jCacheJson").val();
        return val ? eval("(" + val + ")")[key] : ""
      }
    };
    com.init(),
      com.Emitter.applyTo(com),
      module.exports = com
  }),
  define("module/item/1.0.0/baseinfo/promotion/common", ["require", "exports", "module", "module/item/1.0.0/common/common", "lib/gallery/channel/1.0.0/channel"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/common/common")
      , n = t("lib/gallery/channel/1.0.0/channel")
      , a = n.get("item/promotion")
      , r = {
      proInfoData: {
        gIsMarketable: o.gIsMarketable,
        price: {},
        tariff: {},
        evaluation: {},
        specification: o.getCacheJson("specification"),
        countDown: {},
        delivery: {
          isHidden: !0
        },
        qtyBox: {},
        btn: {},
        spike: {},
        shopInfo: {},
        preferential: {},
        promotion: {}
      },
      loadErrorMsg: '数据加载失败，请<a class="jReloadInit" style="color:blue;" href="javascript:;">重试<a>!',
      events: function() {
        o.o.on("click", ".jReloadInit", function() {
          r.emit("reloadInit")
        })
      },
      getStockInfo: function(t) {
        var e = this;
        o.ajax("http://item.yunhou.com/index/ajaxGetStoreData", {}, function(e) {
          t && t(e)
        }, function() {
          $("#jProInfo").html(e.loadErrorMsg)
        })
      },
      getPromotionInfo: function(t) {
        var e = this;
        o.ajax("http://item.yunhou.com/index/ajaxGetPromotionData", {}, function(e) {
          t && t(e)
        }, function() {
          $("#jProInfo").html(e.loadErrorMsg)
        })
      },
      getTariffPrice: function(t) {
        var e = r.proInfoData
          , i = e.promotion
          , n = e.price.showOriginalPrice ? e.price.originalPrice : e.price.scarePrice;
        o.gIsMarketable && i.isCustoms && i.supportDelivery ? o.ajax("http://item.yunhou.com/index/ajaxGetCustomsInfo", {
          price: n,
          productId: o.gProductId,
          taxeFormula: i.taxeFormula,
          tagCode: i.tagCode
        }, function(e) {
          e && e.data && t && t(e.data)
        }, function() {
          t && t()
        }) : t && t()
      },
      on: function(t, e) {
        r.on(t, e)
      },
      getAllData: function(t) {
        var e = this;
        e.getStockInfo(function(i) {
          var n = {};
          $.extend(n, i),
            o.setCacheData("store", i.store),
            e.getPromotionInfo(function(i) {
              $.extend(n, i),
                e.proInfoData.promotion = n,
              t && t(n)
            })
        })
      },
      exProData: function(t) {
        $.extend(!0, this.proInfoData, t)
      }
    };
    $.extend(r, o),
      o.Emitter.applyTo(r),
      r.events(),
      a.listen("info/dataLoaded", function() {
        r.getTariffPrice(function(t) {
          $.extend(r.proInfoData.promotion, {
            customsInfo: t
          }),
            a.fire("com/dataLoaded", r.proInfoData.promotion)
        })
      }),
      i.exports = r
  }),
  define("module/item/1.0.0/baseinfo/promotion/shop-info", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      types: {
        isHidden: {
          isHidden: !0
        },
        getShopStr: function() {
          var t = o.proInfoData.promotion
            , e = t.warehouse
            , i = o.getCacheData("shop-url")
            , n = 0 == i.length ? "" : 'href="' + i + '" target="_blank"'
            , a = 0 == i.length ? "span" : "a"
            , r = o.getCacheData("shop-name")
            , s = o.getCacheData("shop-num")
            , l = ""
            , c = ""
            , u = ""
            , d = "<div>" + o.getCacheData("shop-info") + "</div>"
            , h = ""
            , m = "";
          return s > 1 && (h = '<div>还有<span class="txt-box"><a href="http://spu.yunhou.com/' + o.gProductId + '.html" target="_blank">' + s + "</a></span>个店铺供货</div>"),
          t.distrLimit && "" != t.distrLimit && (c = '<div>预计<span class="txt-box">' + t.distrLimit + "</span>天送达。</div>"),
          e && 0 != e.length && (u = "<div>从" + e + "发货</div>"),
            l = "<" + a + ' class="txt-box" ' + n + ' id="jShopName">' + r + "</" + a + ">",
            m = d + u + c + h,
          {
            isSelfShop: o.boot(o.getCacheData("shop-is-self")),
            nameStr: l,
            allStr: m
          }
        }
      }
    };
    i.exports = {
      setData: function(t) {
        var e = n.types[t];
        o.exProData({
          shopInfo: $.isFunction(e) ? e() : e
        })
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/delivery", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common", "pub/module/local-select/1.0.1/local-select"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = t("pub/module/local-select/1.0.1/local-select")
      , a = {
      types: {
        isHidden: {
          isHidden: !0
        },
        nonDelivery: function() {
          return {
            deliveryInfo: "不可配送"
          }
        },
        groupBuy: function() {
          var t = o.proInfoData.promotion
            , e = (t.store,
            t.groupDataModel)
            , i = o.proInfoData.delivery;
          return !t.supportDelivery || e.pmtStore <= 0 || 0 == t.store || (i.deliveryInfo = ""),
            i
        }
      },
      getLinkageTab: function() {
        n({
          defaultText: "立即选择，您的地址，您做主！",
          areaId: "jItemAdddressInfo",
          selector: "jItemAddress",
          selectedUrl: "http://www.yunhou.com/api/getUserRegion",
          changeCallBackUrl: "http://www.yunhou.com/api/setUserRegion",
          url: "http://www.yunhou.com/api/getRegion/jsonp/",
          degree: 3,
          lastChangeCallBack: function(t) {
            window.location.reload()
          }
        })
      }
    };
    i.exports = {
      getLinkageTab: function() {
        a.getLinkageTab()
      },
      setData: function(t) {
        var e = a.types[t];
        o.exProData({
          delivery: $.isFunction(e) ? e() : e
        })
      }
    }
  }),
  define("pub/module/cart/1.0.0/add-cart", ["require", "exports", "module", "jquery", "lib/ui/box/1.0.1/box", "lib/gallery/ssologin/1.0.0/ssologin", "./cart"], function(t, e, i) {
    "use strict";
    var o = t("jquery")
      , n = t("lib/ui/box/1.0.1/box")
      , a = t("lib/gallery/ssologin/1.0.0/ssologin")
      , r = t("./cart")
      , s = {
      target: "#jMinCart",
      styles: {
        width: 30,
        height: 30,
        border: "2px solid #f03468",
        id: "__addCartCnt",
        "z-index": 200,
        position: "fixed",
        "text-align": "center",
        "border-radius": "50%",
        "background-color": "rgb(255,255,255,0.5)",
        display: "none"
      }
    };
    r.extend = function(t) {
      this.setting = o.extend(!0, {}, this.setting, t || {}),
        this._setStyle(r.setting.styles)
    }
      ,
      r._createThumbnail = function(t) {
        var e, i = this;
        return i._setImg(t),
          e = this.img.clone(),
          o(document.body).append(e),
          e
      }
      ,
      r._destoryThumbnail = function(t) {
        t.remove()
      }
      ,
      r._setStyle = function(t) {
        var e = this;
        e.img.css(t)
      }
      ,
      r.addCart = function(t) {
        var e = ""
          , i = {};
        t instanceof jQuery ? e = t : (e = t.sender,
          i = t.data || {}),
          r.add({
            sender: e,
            data: i,
            callback: function() {
              r._move(e, t)
            },
            error: function(t) {
              t._error ? "-600" === t._error.code || -600 === t._error.code ? a.showDialog() : n.error(t._error.msg, e[0]) : (e.removeClass("btn-disabled"),
                e.prop("disabled", !1))
            }
          })
      }
      ,
      r.img = o("<img />", {
        src: "//s1.bbgstatic.com/pub/img/blank.gif"
      }),
      r._setImg = function(t) {
        this.img.attr("src", t)
      }
      ,
      r._move = function(t, e) {
        var i, n, a, r, l, c, u, d = this, h = o(d.setting.target);
        h.length > 0 && (i = t.attr("data-img"),
        i || (i = e.img),
          n = d._createThumbnail(i),
          a = t.offset(),
          r = h.offset(),
          l = void 0 !== window.pageYOffset ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
          c = {
            top: t.offset().top - l,
            right: o(window).width() - a.left - t.outerWidth() / 2 - s.styles.width / 2
          },
          u = {
            top: r.top - l,
            right: Math.abs(h.width() / 2 - s.styles.width / 2)
          },
          n.css({
            display: "block",
            opacity: "1",
            top: c.top,
            right: c.right
          }),
          n.stop().animate({
            top: u.top - 20,
            right: u.right
          }, "normal", function() {
            n.animate({
              opacity: ".5",
              top: u.top,
              right: 0
            }, function() {
              n.css({
                opacity: "0",
                display: "none"
              }),
                d._destoryThumbnail(n),
              e.success && e.success()
            })
          }))
      }
      ,
      r.extend(s),
      i.exports = r
  }),
  define("module/common/globalObject", ["require", "exports", "module", "jquery", "lib/core/1.0.0/io/request", "lib/ui/dialog/6.0.2/dialog-plus", "lib/gallery/utils/1.0.0/image"], function(t, e, i) {
    "use strict";
    var o, n = t("jquery"), a = t("lib/core/1.0.0/io/request"), r = t("lib/ui/dialog/6.0.2/dialog-plus");
    t("lib/gallery/utils/1.0.0/image");
    return window.console = function() {
      return window.console ? window.console : {
        log: function() {}
      }
    }(),
      o = window.globalObject = this.globalObject = {
        Math: {
          fixed2: function(t) {
            var e, i, o = parseFloat(t);
            if (isNaN(o))
              return "0.00";
            for (o = Math.round(100 * t) / 100,
                   e = o.toString(),
                   i = e.indexOf("."),
                 0 > i && (i = e.length,
                   e += "."); e.length <= i + 2; )
              e += "0";
            return e
          }
        }
      },
      o.delHtmlTag = function(t) {
        return t.replace(/<[^>]+>/g, "")
      }
      ,
      o.isSupport = function() {
        var t = !-[1] && !window.XMLHttpRequest
          , e = t;
        e && n(document.body).prepend('<div style="display:block;text-align:center;padding: 20px 0;background-color: #fed5c7;text-align: center;font-size: 14px;color: #bf440a;">亲，你当前的浏览版本太低，部分功能或者内容可能显示不正常，请升级或者更换你的浏览器，换得更好的浏览体验。</div>')
      }
      ,
      Date.prototype.format = function(t) {
        var e, i;
        t || (t = "yyyy-MM-dd hh:mm:ss"),
          e = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3),
            S: this.getMilliseconds()
          },
        /(y+)/.test(t) && (t = t.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length)));
        for (i in e)
          new RegExp("(" + i + ")").test(t) && (t = t.replace(RegExp.$1, 1 == RegExp.$1.length ? e[i] : ("00" + e[i]).substr(("" + e[i]).length)));
        return t
      }
      ,
      o.AJAX = a,
      o.Dialog = {
        autoTime: 2e3,
        loading: function(t) {
          var e = {
            url: "",
            data: {},
            type: "GET",
            sucCallback: function(t) {},
            errCallback: function(t) {},
            text: "正在加载,请稍后...",
            isModal: !0
          }
            , i = n.extend(!0, {}, e, t || {})
            , o = r({
            id: "_dialogForLoading",
            type: "loading-text",
            content: i.text
          });
          i.isModal ? o.showModal() : o.show(),
            a.jsonp(i, function(t) {
              i.sucCallback && i.sucCallback(t),
                o.remove()
            }, function(t) {
              i.errCallback && i.errCallback(t),
                o.remove()
            })
        },
        confirm: function(t, e, i, o) {
          return r({
            id: "_dialogConfirm",
            type: "question",
            content: t,
            align: "top right",
            okValue: "确定",
            ok: function() {
              e && e()
            },
            cancelValue: "取消",
            cancel: function() {
              i && i()
            }
          }).show(o)
        },
        error: function(t, e, i) {
          return r({
            id: "_dialogError",
            type: "error",
            align: "top",
            content: t
          }).time(e, i || o.Dialog.autoTime)
        },
        warning: function(t, e, i) {
          return r({
            id: "_dialogWarning",
            type: "warning",
            align: "top",
            content: t
          }).time(e, i || o.Dialog.autoTime)
        },
        ok: function(t, e, i) {
          return r({
            id: "_dialogOk",
            type: "ok",
            align: "top",
            content: t
          }).time(e, i || o.Dialog.autoTime)
        },
        tips: function(t, e, i) {
          return r({
            id: "_dialogTips",
            type: "tips",
            align: "top",
            content: t
          }).time(e, i || o.Dialog.autoTime)
        },
        waiting: function(t, e, i, n) {
          var a = r({
            id: "_dialogTips",
            type: "tips",
            align: "top",
            content: '<p class="tips-div">' + (t || "loading...") + "</p>"
          }).time(i, n || o.Dialog.autoTime);
          return e && e(),
            a
        },
        pop: function(t) {
          return r(n.extend({
            id: "_dialogPop"
          }, t)).showModal()
        }
      },
      o
  }),
  define("module/common/jquery.formParams", ["require", "exports", "module", "jquery"], function(t, e, i) {
    "use strict";
    var o = t("jquery")
      , n = /[^\[\]]+/g
      , a = /^[\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?$/
      , r = function(t) {
        return "number" == typeof t ? !0 : "string" != typeof t ? !1 : t.match(a)
      }
      , s = function(t) {
        var e = document.createElement("div");
        return e.innerHTML = t,
        e.innerText || e.textContent
      }
      ;
    o.fn.extend({
      formParams: function(t, e) {
        return "boolean" == typeof t && (e = t,
          t = null ),
          t ? this.setParams(t, e) : "FORM" === this[0].nodeName && this[0].elements ? jQuery(jQuery.makeArray(this[0].elements)).getParams(e) : void 0
      },
      setParams: function(t, e) {
        return this.find("[name]").each(function() {
          var i, n, a, r, l, c = o(this).attr("name"), u = t[c];
          if (c.indexOf("[") > -1) {
            for (i = c.replace(/\]/g, "").split("["),
                   n = 0,
                   a = null ,
                   r = t; a = i[n++]; ) {
              if (!r[a]) {
                r = void 0;
                break
              }
              r = r[a]
            }
            u = r
          }
          e !== !0 && void 0 === u || (null  !== u && void 0 !== u || (u = ""),
          "string" == typeof u && u.indexOf("&") > -1 && (u = s(u)),
            "radio" === this.type ? this.checked = this.value == u : "checkbox" === this.type ? this.checked = u : "placeholder" in document.createElement("input") ? this.value = u : (l = o(this),
            this.value != u && "" !== u && l.data("changed", !0),
              "" === u ? l.data("changed", !1).val(l.attr("placeholder")) : this.value = u))
        }),
          this
      },
      getParams: function(t) {
        var e, i = {};
        return t = void 0 === t ? !1 : t,
          this.each(function() {
            var a, s, l, c, u, d, h, m, p = this, f = p.type && p.type.toLowerCase();
            if ("submit" !== f && p.name && !p.disabled && (a = p.name,
                s = o.data(p, "value") || o.fn.val.call([p]),
                l = a.match(n),
              "radio" !== p.type || p.checked)) {
              for ("checkbox" === p.type && (s = p.checked),
                     u = o(p),
                   u.data("changed") !== !0 && s === u.attr("placeholder") && (s = ""),
                   t && (r(s) ? (d = parseFloat(s),
                     h = d + "",
                   s.indexOf(".") > 0 && (h = d.toFixed(s.split(".")[1].length)),
                   h === s && (s = d)) : "true" === s ? s = !0 : "false" === s && (s = !1),
                   "" === s && (s = void 0)),
                     e = i,
                     m = 0; m < l.length - 1; m++)
                e[l[m]] || (e[l[m]] = {}),
                  e = e[l[m]];
              c = l[l.length - 1],
                e[c] ? (o.isArray(e[c]) || (e[c] = void 0 === e[c] ? [] : [e[c]]),
                  e[c].push(s)) : e[c] || (e[c] = s)
            }
          }),
          i
      }
    })
  }),
  function(t) {
    "function" == typeof define && define.amd ? define("lib/plugins/validation/1.13.1/validate", ["jquery"], t) : t(jQuery)
  }(function(t) {
    t.extend(t.fn, {
      validate: function(e) {
        var i, o, n;
        return this.length ? (i = this[0],
          o = t(i),
          (n = o.data("validator")) ? n : (this.attr("novalidate", "novalidate"),
            n = new t.validator(e,i),
            o.data("validator", n),
          n.settings.onsubmit && (this.validateDelegate(":submit", "click", function(e) {
            n.settings.submitHandler && (n.submitButton = e.target),
            t(e.target).hasClass("cancel") && (n.cancelSubmit = !0),
            void 0 !== t(e.target).attr("formnovalidate") && (n.cancelSubmit = !0)
          }),
            this.submit(function(e) {
              function i() {
                var i, o;
                return n.settings.submitHandler ? (n.submitButton && (i = t("<input type='hidden'/>").attr("name", n.submitButton.name).val(t(n.submitButton).val()).appendTo(n.currentForm)),
                  o = n.settings.submitHandler.call(n, n.currentForm, e),
                n.submitButton && i.remove(),
                  void 0 !== o ? o : !1) : !0
              }
              return n.settings.debug && e.preventDefault(),
                n.cancelSubmit ? (n.cancelSubmit = !1,
                  i()) : n.form() ? n.pendingRequest ? (n.formSubmitted = !0,
                  !1) : i() : (n.focusInvalid(),
                  !1)
            })),
            n)) : void (e && e.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing."))
      },
      valid: function() {
        var e, i;
        return t(this[0]).is("form") ? e = this.validate().form() : (e = !0,
          i = t(this[0].form).validate(),
          this.each(function() {
            e = i.element(this) && e
          })),
          e
      },
      removeAttrs: function(e) {
        var i = {}
          , o = this;
        return t.each(e.split(/\s/), function(t, e) {
          i[e] = o.attr(e),
            o.removeAttr(e)
        }),
          i
      },
      rules: function(e, i) {
        var o, n, a, r, s, l, c = this[0];
        if (e)
          switch (o = t(c.form).data("validator").settings,
            n = o.rules,
            a = t.validator.staticRules(c),
            e) {
            case "add":
              t.extend(a, t.validator.normalizeRule(i)),
                delete a.messages,
                n[c.name] = a,
              i.messages && (o.messages[c.name] = t.extend(o.messages[c.name], i.messages));
              break;
            case "remove":
              return i ? (l = {},
                t.each(i.split(/\s/), function(e, i) {
                  l[i] = a[i],
                    delete a[i],
                  "required" === i && t(c).removeAttr("aria-required")
                }),
                l) : (delete n[c.name],
                a)
          }
        return r = t.validator.normalizeRules(t.extend({}, t.validator.classRules(c), t.validator.attributeRules(c), t.validator.dataRules(c), t.validator.staticRules(c)), c),
        r.required && (s = r.required,
          delete r.required,
          r = t.extend({
            required: s
          }, r),
          t(c).attr("aria-required", "true")),
        r.remote && (s = r.remote,
          delete r.remote,
          r = t.extend(r, {
            remote: s
          })),
          r
      }
    }),
      t.extend(t.expr[":"], {
        blank: function(e) {
          return !t.trim("" + t(e).val())
        },
        filled: function(e) {
          return !!t.trim("" + t(e).val())
        },
        unchecked: function(e) {
          return !t(e).prop("checked")
        }
      }),
      t.validator = function(e, i) {
        this.settings = t.extend(!0, {}, t.validator.defaults, e),
          this.currentForm = i,
          this.init()
      }
      ,
      t.validator.format = function(e, i) {
        return 1 === arguments.length ? function() {
          var i = t.makeArray(arguments);
          return i.unshift(e),
            t.validator.format.apply(this, i)
        }
          : (arguments.length > 2 && i.constructor !== Array && (i = t.makeArray(arguments).slice(1)),
        i.constructor !== Array && (i = [i]),
          t.each(i, function(t, i) {
            e = e.replace(new RegExp("\\{" + t + "\\}","g"), function() {
              return i
            })
          }),
          e)
      }
      ,
      t.extend(t.validator, {
        defaults: {
          messages: {},
          groups: {},
          rules: {},
          errorClass: "error",
          validClass: "valid",
          errorElement: "label",
          focusCleanup: !1,
          focusInvalid: !0,
          errorContainer: t([]),
          errorLabelContainer: t([]),
          onsubmit: !0,
          ignore: ":hidden",
          ignoreTitle: !1,
          onfocusin: function(t) {
            this.lastActive = t,
            this.settings.focusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, t, this.settings.errorClass, this.settings.validClass),
              this.hideThese(this.errorsFor(t)))
          },
          onfocusout: function(t) {
            this.checkable(t) || !(t.name in this.submitted) && this.optional(t) || this.element(t)
          },
          onkeyup: function(t, e) {
            9 === e.which && "" === this.elementValue(t) || (t.name in this.submitted || t === this.lastElement) && this.element(t)
          },
          onclick: function(t) {
            t.name in this.submitted ? this.element(t) : t.parentNode.name in this.submitted && this.element(t.parentNode)
          },
          highlight: function(e, i, o) {
            "radio" === e.type ? this.findByName(e.name).addClass(i).removeClass(o) : t(e).addClass(i).removeClass(o)
          },
          unhighlight: function(e, i, o) {
            "radio" === e.type ? this.findByName(e.name).removeClass(i).addClass(o) : t(e).removeClass(i).addClass(o)
          }
        },
        setDefaults: function(e) {
          t.extend(t.validator.defaults, e)
        },
        messages: {
          required: "This field is required.",
          remote: "Please fix this field.",
          email: "Please enter a valid email address.",
          url: "Please enter a valid URL.",
          date: "Please enter a valid date.",
          dateISO: "Please enter a valid date ( ISO ).",
          number: "Please enter a valid number.",
          digits: "Please enter only digits.",
          creditcard: "Please enter a valid credit card number.",
          equalTo: "Please enter the same value again.",
          maxlength: t.validator.format("Please enter no more than {0} characters."),
          minlength: t.validator.format("Please enter at least {0} characters."),
          rangelength: t.validator.format("Please enter a value between {0} and {1} characters long."),
          range: t.validator.format("Please enter a value between {0} and {1}."),
          max: t.validator.format("Please enter a value less than or equal to {0}."),
          min: t.validator.format("Please enter a value greater than or equal to {0}.")
        },
        autoCreateRanges: !1,
        prototype: {
          init: function() {
            function e(e) {
              var i = t(this[0].form).data("validator")
                , o = "on" + e.type.replace(/^validate/, "")
                , n = i.settings;
              n[o] && !this.is(n.ignore) && n[o].call(i, this[0], e)
            }
            this.labelContainer = t(this.settings.errorLabelContainer),
              this.errorContext = this.labelContainer.length && this.labelContainer || t(this.currentForm),
              this.containers = t(this.settings.errorContainer).add(this.settings.errorLabelContainer),
              this.submitted = {},
              this.valueCache = {},
              this.pendingRequest = 0,
              this.pending = {},
              this.invalid = {},
              this.reset();
            var i, o = this.groups = {};
            t.each(this.settings.groups, function(e, i) {
              "string" == typeof i && (i = i.split(/\s/)),
                t.each(i, function(t, i) {
                  o[i] = e
                })
            }),
              i = this.settings.rules,
              t.each(i, function(e, o) {
                i[e] = t.validator.normalizeRule(o)
              }),
              t(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']", "focusin focusout keyup", e).validateDelegate("select, option, [type='radio'], [type='checkbox']", "click", e),
            this.settings.invalidHandler && t(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler),
              t(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required", "true")
          },
          form: function() {
            return this.checkForm(),
              t.extend(this.submitted, this.errorMap),
              this.invalid = t.extend({}, this.errorMap),
            this.valid() || t(this.currentForm).triggerHandler("invalid-form", [this]),
              this.showErrors(),
              this.valid()
          },
          checkForm: function() {
            this.prepareForm();
            for (var t = 0, e = this.currentElements = this.elements(); e[t]; t++)
              this.check(e[t]);
            return this.valid()
          },
          element: function(e) {
            var i = this.clean(e)
              , o = this.validationTargetFor(i)
              , n = !0;
            return this.lastElement = o,
              void 0 === o ? delete this.invalid[i.name] : (this.prepareElement(o),
                this.currentElements = t(o),
                n = this.check(o) !== !1,
                n ? delete this.invalid[o.name] : this.invalid[o.name] = !0),
              t(e).attr("aria-invalid", !n),
            this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
              this.showErrors(),
              n
          },
          showErrors: function(e) {
            if (e) {
              t.extend(this.errorMap, e),
                this.errorList = [];
              for (var i in e)
                this.errorList.push({
                  message: e[i],
                  element: this.findByName(i)[0]
                });
              this.successList = t.grep(this.successList, function(t) {
                return !(t.name in e)
              })
            }
            this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
          },
          resetForm: function() {
            t.fn.resetForm && t(this.currentForm).resetForm(),
              this.submitted = {},
              this.lastElement = null ,
              this.prepareForm(),
              this.hideErrors(),
              this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid")
          },
          numberOfInvalids: function() {
            return this.objectLength(this.invalid)
          },
          objectLength: function(t) {
            var e, i = 0;
            for (e in t)
              i++;
            return i
          },
          hideErrors: function() {
            this.hideThese(this.toHide)
          },
          hideThese: function(t) {
            t.not(this.containers).text(""),
              this.addWrapper(t).hide()
          },
          valid: function() {
            return 0 === this.size()
          },
          size: function() {
            return this.errorList.length
          },
          focusInvalid: function() {
            if (this.settings.focusInvalid)
              try {
                t(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin");
              } catch (e) {}
          },
          findLastActive: function() {
            var e = this.lastActive;
            return e && 1 === t.grep(this.errorList, function(t) {
                return t.element.name === e.name
              }).length && e
          },
          elements: function() {
            var e = this
              , i = {};
            return t(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled], [readonly]").not(this.settings.ignore).filter(function() {
              return !this.name && e.settings.debug && window.console && console.error("%o has no name assigned", this),
                this.name in i || !e.objectLength(t(this).rules()) ? !1 : (i[this.name] = !0,
                  !0)
            })
          },
          clean: function(e) {
            return t(e)[0]
          },
          errors: function() {
            var e = this.settings.errorClass.split(" ").join(".");
            return t(this.settings.errorElement + "." + e, this.errorContext)
          },
          reset: function() {
            this.successList = [],
              this.errorList = [],
              this.errorMap = {},
              this.toShow = t([]),
              this.toHide = t([]),
              this.currentElements = t([])
          },
          prepareForm: function() {
            this.reset(),
              this.toHide = this.errors().add(this.containers)
          },
          prepareElement: function(t) {
            this.reset(),
              this.toHide = this.errorsFor(t)
          },
          elementValue: function(e) {
            var i, o = t(e), n = e.type;
            return "radio" === n || "checkbox" === n ? t("input[name='" + e.name + "']:checked").val() : "number" === n && "undefined" != typeof e.validity ? e.validity.badInput ? !1 : o.val() : (i = o.val(),
              "string" == typeof i ? i.replace(/\r/g, "") : i)
          },
          check: function(e) {
            e = this.validationTargetFor(this.clean(e));
            var i, o, n, a = t(e).rules(), r = t.map(a, function(t, e) {
              return e
            }).length, s = !1, l = this.elementValue(e);
            for (o in a) {
              n = {
                method: o,
                parameters: a[o]
              };
              try {
                if (i = t.validator.methods[o].call(this, l, e, n.parameters),
                  "dependency-mismatch" === i && 1 === r) {
                  s = !0;
                  continue
                }
                if (s = !1,
                  "pending" === i)
                  return void (this.toHide = this.toHide.not(this.errorsFor(e)));
                if (!i)
                  return this.formatAndAdd(e, n),
                    !1
              } catch (c) {
                throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + e.id + ", check the '" + n.method + "' method.", c),
                  c
              }
            }
            if (!s)
              return this.objectLength(a) && this.successList.push(e),
                !0
          },
          customDataMessage: function(e, i) {
            return t(e).data("msg" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()) || t(e).data("msg")
          },
          customMessage: function(t, e) {
            var i = this.settings.messages[t];
            return i && (i.constructor === String ? i : i[e])
          },
          findDefined: function() {
            for (var t = 0; t < arguments.length; t++)
              if (void 0 !== arguments[t])
                return arguments[t]
          },
          defaultMessage: function(e, i) {
            return this.findDefined(this.customMessage(e.name, i), this.customDataMessage(e, i), !this.settings.ignoreTitle && e.title || void 0, t.validator.messages[i], "<strong>Warning: No message defined for " + e.name + "</strong>")
          },
          formatAndAdd: function(e, i) {
            var o = this.defaultMessage(e, i.method)
              , n = /\$?\{(\d+)\}/g;
            "function" == typeof o ? o = o.call(this, i.parameters, e) : n.test(o) && (o = t.validator.format(o.replace(n, "{$1}"), i.parameters)),
              this.errorList.push({
                message: o,
                element: e,
                method: i.method
              }),
              this.errorMap[e.name] = o,
              this.submitted[e.name] = o
          },
          addWrapper: function(t) {
            return this.settings.wrapper && (t = t.add(t.parent(this.settings.wrapper))),
              t
          },
          defaultShowErrors: function() {
            var t, e, i, o, n = this.settings;
            for (t = 0; this.errorList[t]; t++)
              i = this.errorList[t],
              n.highlight && n.highlight.call(this, i.element, n.errorClass, n.validClass),
                o = i.message,
              n.customMessage && (o = n.customMessage(o, i)),
                this.showLabel(i.element, o);
            if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)),
                n.success)
              for (t = 0; this.successList[t]; t++)
                this.showLabel(this.successList[t]);
            if (n.unhighlight)
              for (t = 0,
                     e = this.validElements(); e[t]; t++)
                n.unhighlight.call(this, e[t], n.errorClass, n.validClass);
            this.toHide = this.toHide.not(this.toShow),
              this.hideErrors(),
              this.addWrapper(this.toShow).show()
          },
          validElements: function() {
            return this.currentElements.not(this.invalidElements())
          },
          invalidElements: function() {
            return t(this.errorList).map(function() {
              return this.element
            })
          },
          showLabel: function(e, i) {
            var o, n, a, r = this.errorsFor(e), s = this.idOrName(e), l = t(e).attr("aria-describedby");
            r.length ? (r.removeClass(this.settings.validClass).addClass(this.settings.errorClass),
              r.html(i)) : (r = t("<" + this.settings.errorElement + ">").attr("id", s + "-error").addClass(this.settings.errorClass).html(i || ""),
              o = r,
            this.settings.wrapper && (o = r.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()),
              this.labelContainer.length ? this.labelContainer.append(o) : this.settings.errorPlacement ? this.settings.errorPlacement(o, t(e)) : o.insertAfter(e),
              r.is("label") ? r.attr("for", s) : 0 === r.parents("label[for='" + s + "']").length && (a = r.attr("id").replace(/(:|\.|\[|\])/g, "\\$1"),
                l ? l.match(new RegExp("\\b" + a + "\\b")) || (l += " " + a) : l = a,
                t(e).attr("aria-describedby", l),
                n = this.groups[e.name],
              n && t.each(this.groups, function(e, i) {
                i === n && t("[name='" + e + "']", this.currentForm).attr("aria-describedby", r.attr("id"))
              }))),
            !i && this.settings.success && (r.text(""),
              "string" == typeof this.settings.success ? r.addClass(this.settings.success) : this.settings.success(r, e)),
              this.toShow = this.toShow.add(r)
          },
          errorsFor: function(e) {
            var i = this.idOrName(e)
              , o = t(e).attr("aria-describedby")
              , n = "label[for='" + i + "'], label[for='" + i + "'] *"
              , a = this.settings.errorElement;
            return "label" !== a && (n = n + ", " + a + '[for="' + i + '"]'),
            o && (n = n + ", #" + o.replace(/\s+/g, ", #")),
              this.errors().filter(n)
          },
          idOrName: function(t) {
            return this.groups[t.name] || (this.checkable(t) ? t.name : t.id || t.name)
          },
          validationTargetFor: function(e) {
            return this.checkable(e) && (e = this.findByName(e.name)),
              t(e).not(this.settings.ignore)[0]
          },
          checkable: function(t) {
            return /radio|checkbox/i.test(t.type)
          },
          findByName: function(e) {
            return t(this.currentForm).find("[name='" + e + "']")
          },
          getLength: function(e, i) {
            switch (i.nodeName.toLowerCase()) {
              case "select":
                return t("option:selected", i).length;
              case "input":
                if (this.checkable(i))
                  return this.findByName(i.name).filter(":checked").length
            }
            return e.length
          },
          depend: function(t, e) {
            return this.dependTypes[typeof t] ? this.dependTypes[typeof t](t, e) : !0
          },
          dependTypes: {
            "boolean": function(t) {
              return t
            },
            string: function(e, i) {
              return !!t(e, i.form).length
            },
            "function": function(t, e) {
              return t(e)
            }
          },
          optional: function(e) {
            var i = this.elementValue(e);
            return !t.validator.methods.required.call(this, i, e) && "dependency-mismatch"
          },
          startRequest: function(t) {
            this.pending[t.name] || (this.pendingRequest++,
              this.pending[t.name] = !0)
          },
          stopRequest: function(e, i) {
            this.pendingRequest--,
            this.pendingRequest < 0 && (this.pendingRequest = 0),
              delete this.pending[e.name],
              i && 0 === this.pendingRequest && this.formSubmitted && this.form() ? (t(this.currentForm).submit(),
                this.formSubmitted = !1) : !i && 0 === this.pendingRequest && this.formSubmitted && (t(this.currentForm).triggerHandler("invalid-form", [this]),
                this.formSubmitted = !1)
          },
          previousValue: function(e) {
            var i = t(e)
              , o = i.data("previousValue");
            return o ? o : (i.data("previousValue", o = {
              old: null ,
              valid: !0,
              message: this.defaultMessage(e, "remote")
            }),
              o)
          }
        },
        classRuleSettings: {
          required: {
            required: !0
          },
          email: {
            email: !0
          },
          url: {
            url: !0
          },
          date: {
            date: !0
          },
          dateISO: {
            dateISO: !0
          },
          number: {
            number: !0
          },
          digits: {
            digits: !0
          },
          creditcard: {
            creditcard: !0
          }
        },
        addClassRules: function(e, i) {
          e.constructor === String ? this.classRuleSettings[e] = i : t.extend(this.classRuleSettings, e)
        },
        classRules: function(e) {
          var i = {}
            , o = t(e).attr("class");
          return o && t.each(o.split(" "), function() {
            this in t.validator.classRuleSettings && t.extend(i, t.validator.classRuleSettings[this])
          }),
            i
        },
        attributeRules: function(e) {
          var i, o, n = {}, a = t(e), r = e.getAttribute("type");
          for (i in t.validator.methods)
            "required" === i ? (o = e.getAttribute(i),
            "" === o && (o = !0),
              o = !!o) : o = a.attr(i),
            /min|max/.test(i) && (null  === r || /number|range|text/.test(r)) && (o = Number(o)),
              o || 0 === o ? n[i] = o : r === i && "range" !== r && (n[i] = !0);
          return n.maxlength && /-1|2147483647|524288/.test(n.maxlength) && delete n.maxlength,
            n
        },
        dataRules: function(e) {
          var i, o, n = {}, a = t(e);
          for (i in t.validator.methods)
            o = a.data("rule" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()),
            void 0 !== o && (n[i] = o);
          return n
        },
        staticRules: function(e) {
          var i = {}
            , o = t(e.form).data("validator");
          return o.settings.rules && (i = t.validator.normalizeRule(o.settings.rules[e.name]) || {}),
            i
        },
        normalizeRules: function(e, i) {
          return t.each(e, function(o, n) {
            if (n === !1)
              return void delete e[o];
            if (n.param || n.depends) {
              var a = !0;
              switch (typeof n.depends) {
                case "string":
                  a = !!t(n.depends, i.form).length;
                  break;
                case "function":
                  a = n.depends.call(i, i)
              }
              a ? e[o] = void 0 !== n.param ? n.param : !0 : delete e[o]
            }
          }),
            t.each(e, function(o, n) {
              e[o] = t.isFunction(n) ? n(i) : n
            }),
            t.each(["minlength", "maxlength"], function() {
              e[this] && (e[this] = Number(e[this]))
            }),
            t.each(["rangelength", "range"], function() {
              var i;
              e[this] && (t.isArray(e[this]) ? e[this] = [Number(e[this][0]), Number(e[this][1])] : "string" == typeof e[this] && (i = e[this].replace(/[\[\]]/g, "").split(/[\s,]+/),
                e[this] = [Number(i[0]), Number(i[1])]))
            }),
          t.validator.autoCreateRanges && (null  != e.min && null  != e.max && (e.range = [e.min, e.max],
            delete e.min,
            delete e.max),
          null  != e.minlength && null  != e.maxlength && (e.rangelength = [e.minlength, e.maxlength],
            delete e.minlength,
            delete e.maxlength)),
            e
        },
        normalizeRule: function(e) {
          if ("string" == typeof e) {
            var i = {};
            t.each(e.split(/\s/), function() {
              i[this] = !0
            }),
              e = i
          }
          return e
        },
        addMethod: function(e, i, o) {
          t.validator.methods[e] = i,
            t.validator.messages[e] = void 0 !== o ? o : t.validator.messages[e],
          i.length < 3 && t.validator.addClassRules(e, t.validator.normalizeRule(e))
        },
        methods: {
          required: function(e, i, o) {
            if (!this.depend(o, i))
              return "dependency-mismatch";
            if ("select" === i.nodeName.toLowerCase()) {
              var n = t(i).val();
              return n && n.length > 0
            }
            return this.checkable(i) ? this.getLength(e, i) > 0 : t.trim(e).length > 0
          },
          email: function(t, e) {
            return this.optional(e) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)
          },
          url: function(t, e) {
            return this.optional(e) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(t)
          },
          date: function(t, e) {
            return this.optional(e) || !/Invalid|NaN/.test(new Date(t).toString())
          },
          dateISO: function(t, e) {
            return this.optional(e) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(t)
          },
          number: function(t, e) {
            return this.optional(e) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)
          },
          digits: function(t, e) {
            return this.optional(e) || /^\d+$/.test(t)
          },
          creditcard: function(t, e) {
            if (this.optional(e))
              return "dependency-mismatch";
            if (/[^0-9 \-]+/.test(t))
              return !1;
            var i, o, n = 0, a = 0, r = !1;
            if (t = t.replace(/\D/g, ""),
              t.length < 13 || t.length > 19)
              return !1;
            for (i = t.length - 1; i >= 0; i--)
              o = t.charAt(i),
                a = parseInt(o, 10),
              r && (a *= 2) > 9 && (a -= 9),
                n += a,
                r = !r;
            return n % 10 === 0
          },
          minlength: function(e, i, o) {
            var n = t.isArray(e) ? e.length : this.getLength(e, i);
            return this.optional(i) || n >= o
          },
          maxlength: function(e, i, o) {
            var n = t.isArray(e) ? e.length : this.getLength(e, i);
            return this.optional(i) || o >= n
          },
          rangelength: function(e, i, o) {
            var n = t.isArray(e) ? e.length : this.getLength(e, i);
            return this.optional(i) || n >= o[0] && n <= o[1]
          },
          min: function(t, e, i) {
            return this.optional(e) || t >= i
          },
          max: function(t, e, i) {
            return this.optional(e) || i >= t
          },
          range: function(t, e, i) {
            return this.optional(e) || t >= i[0] && t <= i[1]
          },
          equalTo: function(e, i, o) {
            var n = t(o);
            return this.settings.onfocusout && n.unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
              t(i).valid()
            }),
            e === n.val()
          },
          remote: function(e, i, o) {
            if (this.optional(i))
              return "dependency-mismatch";
            var n, a, r = this.previousValue(i), s = o.censor;
            return s && t.isFunction(s) || (s = function(t) {
                return t === !0 || "true" === t
              }
            ),
            this.settings.messages[i.name] || (this.settings.messages[i.name] = {}),
              r.originalMessage = this.settings.messages[i.name].remote,
              this.settings.messages[i.name].remote = r.message,
              o = "string" == typeof o && {
                  url: o
                } || o,
              r.old === e ? r.valid : (r.old = e,
                n = this,
                this.startRequest(i),
                a = {},
                a[i.name] = e,
                t.ajax(t.extend(!0, {
                  url: o,
                  mode: "abort",
                  port: "validate" + i.name,
                  dataType: "json",
                  data: a,
                  context: n.currentForm,
                  success: function(o) {
                    var a, l, c, u = s(o);
                    n.settings.messages[i.name].remote = r.originalMessage,
                      u ? (c = n.formSubmitted,
                        n.prepareElement(i),
                        n.formSubmitted = c,
                        n.successList.push(i),
                        delete n.invalid[i.name],
                        n.showErrors()) : (a = {},
                        l = o || n.defaultMessage(i, "remote"),
                        a[i.name] = r.message = t.isFunction(l) ? l(e) : l,
                        n.invalid[i.name] = !0,
                        n.showErrors(a)),
                      r.valid = u,
                      n.stopRequest(i, u)
                  }
                }, o)),
                "pending")
          }
        }
      }),
      t.format = function() {
        throw "$.format has been deprecated. Please use $.validator.format instead."
      }
    ;
    var e, i = {};
    return t.ajaxPrefilter ? t.ajaxPrefilter(function(t, e, o) {
      var n = t.port;
      "abort" === t.mode && (i[n] && i[n].abort(),
        i[n] = o)
    }) : (e = t.ajax,
        t.ajax = function(o) {
          var n = ("mode" in o ? o : t.ajaxSettings).mode
            , a = ("port" in o ? o : t.ajaxSettings).port;
          return "abort" === n ? (i[a] && i[a].abort(),
            i[a] = e.apply(this, arguments),
            i[a]) : e.apply(this, arguments)
        }
    ),
      t.extend(t.fn, {
        validateDelegate: function(e, i, o) {
          return this.bind(i, function(i) {
            var n = t(i.target);
            return n.is(e) ? o.apply(n, arguments) : void 0
          })
        }
      }),
      t.validator
  }),
  define("lib/plugins/validation/1.13.1/validator", ["require", "exports", "module", "jquery", "./validate"], function(t, e, i) {
    "use strict";
    var o = t("jquery")
      , n = t("./validate")
      , a = /[A-Z]/g
      , r = function(t) {
        return t.replace(a, function(t) {
          return "-" + t.toLowerCase()
        })
      }
      , s = o.isEmptyObject
      , l = function(t) {
        var e = o(t).find("input, select, textarea").not(":submit, :reset, :image, [disabled], [readonly]")
          , i = {};
        return e.each(function(t, e) {
          var n, a, l = e.ie || e.name, c = o(e).data(), u = {};
          for (n in c)
            c.hasOwnProperty(n) && (a = r(n)) && 0 === a.indexOf("validate-") && c[n] && (u[a.substring(9)] = c[n]);
          s(u) || (i[l] = u)
        }),
          i
      }
      ;
    n.validate = function(t, e) {
      var i, a, r = o(t);
      return r.is("form") || (r = r.find("form")),
        r.length ? (i = r[0],
          (a = o(i).data("validator")) ? a : (r.attr("novalidate", "novalidate"),
            e.messages = o.extend(!0, l(i), e.messages),
            a = new n(e,i),
            o(i).data("validator", a),
          a.settings.onsubmit && (r.validateDelegate(":submit", "click", function(t) {
            a.settings.submitHandler && (a.submitButton = t.target),
            o(t.target).hasClass("cancel") && (a.cancelSubmit = !0),
            void 0 !== o(t.target).attr("formnovalidate") && (a.cancelSubmit = !0)
          }),
            r.submit(function(t) {
              function e() {
                var e;
                return a.settings.submitHandler ? (a.submitButton && (e = o('<input type="hidden" />').attr("name", a.submitButton.name).val(o(a.submitButton).val()).appendTo(a.currentForm)),
                  a.settings.submitHandler.call(a, a.currentForm, t),
                a.submitButton && e.remove(),
                  !1) : !0
              }
              return a.settings.debug && t.preventDefault(),
                a.cancelSubmit ? (a.cancelSubmit = !1,
                  e()) : a.form() ? a.pendingRequest ? (a.formSubmitted = !0,
                  !1) : e() : (a.focusInvalid(),
                  !1)
            })),
            a)) : void (e && e.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing."))
    }
      ,
      i.exports = n
  }),
  define("module/common/jquery.validate.method", ["require", "exports", "module", "jquery", "lib/plugins/validation/1.13.1/validator"], function(t, e, i) {
    "use strict";
    var o, n, a = t("jquery");
    t("lib/plugins/validation/1.13.1/validator"),
      o = {
        required: "必填字段",
        remote: "Please fix this field.",
        email: "邮箱地址不正确",
        url: "网址不正确",
        date: "日期格式不正确",
        dateISO: "Please enter a valid date ( ISO ).",
        number: "请输入数字",
        digits: "Please enter only digits.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        maxlength: a.validator.format("最多可输入 {0} 个字符"),
        minlength: a.validator.format("至少输入 {0} 个字符"),
        rangelength: a.validator.format("{0}<= 字符数 <={1}"),
        range: a.validator.format("Please enter a value between {0} and {1}."),
        max: a.validator.format("Please enter a value less than or equal to {0}."),
        min: a.validator.format("最小的数值为 {0}.")
      },
      n = {
        mobile: function(t, e) {
          return this.optional(e) || /^1[\d]{10}$/.test(t)
        },
        fixedTel: function(t, e) {
          return this.optional(e) || /^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/.test(t)
        },
        SpChar: function(t, e) {
          return this.optional(e) || !/[~#^$@%&!*'<>]/gi.test(t)
        }
      },
      a.extend(a.validator.methods, n),
      o = {
        SpChar: "不能输入~#^$@%&!*'<>等特殊字符"
      },
      a.extend(a.validator.messages, o)
  }),
  define("module/common/minLogin", ["require", "exports", "module", "lib/core/1.0.0/utils/util", "lib/gallery/ssologin/1.0.0/ssologin"], function(t, e, i) {
    "use strict";
    var o = t("lib/core/1.0.0/utils/util")
      , n = t("lib/gallery/ssologin/1.0.0/ssologin");
    e.login = o.deprecate(n.showDialog, "miniLogin.login(): Use gallery/ssologin/1.0.0 instead")
  }),
  define("module/common/url", ["require", "exports", "module"], function(t, e, i) {
    var o = {
      cart: "http://api.cart.yunhou.com/",
      mall: "http://api.mall.yunhou.com/",
      item: "http://item.yunhou.com/",
      addr: "http://www.yunhou.com/",
      settlement: "http://api.cart.yunhou.com/",
      baseUrl: "http://" + window.location.host + "/page1/ajax/",
      shopAddress: "http://shop.yunhou.com/"
    }
      , n = {
      List: {
        price: "/search/getAsyncData",
        getPrefer: "http://search.yunhou.com/product/productInfo"
      },
      Img: {
        blank: "//static5.bubugao.com/public/img/blank.gif"
      },
      Login: {
        minLogin: "https://ssl.yunhou.com/login/login-min.php",
        login: "https://ssl.yunhou.com/login/login.php"
      },
      Time: {
        current: "http://api.mall.yunhou.com/Time"
      },
      Cart: {
        get: o.cart + "cart/get",
        add: o.cart + "cart/add",
        fastBuy: o.cart + "cart/fastBuy",
        checked: o.cart + "cart/checked",
        update: o.cart + "cart/update",
        del: o.cart + "cart/del",
        emptyCart: o.cart + "cart/clear",
        setShippingMethod: o.cart + "cart/selectDelivery"
      },
      actCart: {
        get: o.cart + "actCart/get",
        update: o.cart + "actCart/update",
        del: o.cart + "actCart/remove",
        price: o.mall + "product/productInfo"
      },
      Goods: {
        col: o.mall + "product/addfavorite",
        hotRank: o.item + "product/hotrank",
        checkFavorite: o.mall + "product/checkFavorite",
        arrival: o.mall + "product/subscribe"
      },
      Search: {
        suggest: "http://api.search.yunhou.com/bubugao-search-server/api/search",
        all: "http://search.yunhou.com",
        keyword: "http://api.mall.yunhou.com/HotKeyword/getHotKeywords"
      },
      Shop: {
        price: "http://shop.yunhou.com/shop/getAsyncData",
        col: o.mall + "shop/addfavorite",
        getListIconInfo: "http://search.yunhou.com/product/labelInfo"
      },
      MinCart: {
        get: o.cart + "cart/miniGet",
        getCount: o.cart + "cart/getSimple",
        del: o.cart + "cart/miniDel"
      },
      Item: {
        cms: o.item + "index/ajaxGetCommentData",
        history: o.item + "index/ajaxGetHistoryList",
        info: o.item + "index/ajaxGetData"
      },
      Addr: {
        selectedUrl: o.addr + "api/getUserRegion",
        changeCallBackUrl: o.addr + "api/setUserRegion",
        url: o.addr + "api/getRegion/jsonp/"
      },
      settlement: {
        cart: "http://cart.yunhou.com/cart.shtml",
        buyAtOnce: "http://cart.yunhou.com/buy-at-once.shtml",
        buyNow: "http://cart.yunhou.com/buy-now.shtml",
        getSettlementList: o.settlement + "ordercheck/get",
        getMention: o.settlement + "address/getZtd",
        setMention: o.settlement + "address/selectSelfPoint",
        setElectricInfo: o.settlement + "ordercheck/saveJlSaler",
        saveAddr: o.settlement + "address/saveAddr",
        selectAddr: o.settlement + "address/selectAddr",
        setDefaultAddr: o.settlement + "address/setDefAddrs",
        selectIdCard: o.settlement + "ordercheck/selectIdCard",
        saveInvoiceInfo: o.settlement + "tax/saveTax",
        noInvoice: o.settlement + "tax/cancelTax",
        deleteAddr: o.settlement + "address/delAddrs",
        useOffers: o.settlement + "coupon/useCoupon",
        cancelOffers: o.settlement + "coupon/cancelCoupon",
        couponList: o.settlement + "coupon/userCoupons",
        shopDeliveryList: o.settlement + "ordercheck/shopDelivery",
        selectDeliveryType: o.settlement + "ordercheck/selectDelivery",
        leaveMsg: o.settlement + "ordercheck/saveMemo",
        subOrder: o.settlement + "order/create",
        getInvoiceList: "http://api.cart.yunhou.com/tax/taxContentList",
        addReferrer: o.settlement + "ordercheck/saveRecommender",
        verified: {
          add: o.settlement + "ordercheck/saveIdCard",
          update: o.settlement + "ordercheck/getIdCard",
          updateName: o.settlement + "ordercheck/saveIdCard",
          del: o.settlement + "ordercheck/delIdCard"
        }
      },
      getUserInfo: o.mall + "member/getContact",
      UC: {
        getVipInfo: "http://i.yunhou.com/vip-card/info",
        login: "https://ssl.yunhou.com/login/login.php",
        regist: "https://ssl.yunhou.com/login/reg.php",
        index: "http://i.yunhou.com/",
        logout: "https://ssl.yunhou.com/bubugao-passport/logout"
      },
      mendian: "http://shop.yunhou.com/shop/deliveryList"
    };
    i.exports = {
      Goods: n.Goods,
      Shop: n.Shop,
      Item: n.Item,
      Cart: n.Cart,
      settlement: n.settlement,
      getUserInfo: n.getUserInfo,
      actCart: n.actCart,
      Addr: n.Addr
    }
  }),
  define("module/common/cart/1.0.0/arrival-notice", ["require", "exports", "module", "jquery", "module/common/globalObject", "module/common/jquery.formParams", "module/common/jquery.validate.method", "lib/ui/dialog/6.0.2/dialog-plus", "module/common/minLogin", "module/common/url", "lib/core/1.0.0/io/cookie"], function(t, e, i) {
    var o, n, a, r, s, l = t("jquery");
    t("module/common/globalObject"),
      t("module/common/jquery.formParams"),
      t("module/common/jquery.validate.method"),
      o = t("lib/ui/dialog/6.0.2/dialog-plus"),
      n = t("module/common/minLogin"),
      a = t("module/common/url"),
      r = t("lib/core/1.0.0/io/cookie"),
      s = {
        defaultSetting: {
          event: !1,
          loginCallBack: function() {
            n.login(function() {
              r.set("_notice", "click"),
                location.reload()
            })
          },
          subCallBack: function() {}
        },
        _dialog: null ,
        btn: null ,
        init: function() {
          var t = this;
          t.defaultSetting.event ? t.btn.click(function() {
            t.getUserInfo()
          }) : t.getUserInfo()
        },
        addPop: function(t) {
          var e = this;
          r.set("_notice", null ),
            e._dialog = o({
              title: "到货通知",
              width: 500,
              content: e.createForm(t)
            }).showModal(),
            e.bindEvent()
        },
        createForm: function(t) {
          var e = []
            , i = {
            mobile: "",
            email: ""
          };
          return i = l.extend(i, t),
            e.push('<div class="arrivalnotice" id="jArrivalnotice">'),
            e.push("	<div>该货品暂时缺货，请在下面输入您的邮箱地址或手机号码，当我们有现货供应时，我们将通过邮件和短信通知您！</div>"),
            e.push('	<form class="form-horizontal" id="jAnForm">'),
            e.push('		<div class="form-group">'),
            e.push('			<label class="control-label"><em class="txt-red">*</em>邮箱地址：</label>'),
            e.push('			<div class="form-control"><input class="input-text" name="email" value="' + i.email + '"/></div>'),
            e.push("		</div>"),
            e.push('		<div class="form-group">'),
            e.push('			<label class="control-label">手机号码：</label>'),
            e.push('			<div class="form-control"><input class="input-text" name="mobile" value="' + i.mobile + '"/></div>'),
            e.push("		</div>"),
            e.push('		<div class="form-btn"><a class="btn btn-m btn-sec" id="jArrivalnoticeBtn">提交</a></div>'),
            e.push("	</form>"),
            e.push("</div>"),
            e.join("")
        },
        validateForm: function() {
          return l("#jAnForm").validate({
            rules: {
              email: {
                required: !0,
                email: !0
              },
              mobile: {
                mobile: !0
              }
            },
            messages: {
              email: {
                required: "邮件地址不能为空",
                email: "请输入正确的邮件地址"
              },
              mobile: {
                mobile: "请输入正确的手机号码"
              }
            }
          }),
            l("#jAnForm").submit(function() {
              return !1
            }),
            l("#jAnForm").valid()
        },
        ajax: function(t, e) {
          var i = this;
          globalObject.AJAX.jsonp({
            url: t
          }, function(t) {
            e && e(t)
          }, function(t) {
            if (t._error) {
              var o = t._error.code;
              "-100" == o ? i.defaultSetting.loginCallBack() : "-101" == o ? e && e({}) : globalObject.Dialog.error(t._error.msg, i.btn)
            }
          })
        },
        getUserInfo: function() {
          var t = this;
          t.ajax(a.getUserInfo, function(e) {
            t.addPop(e)
          })
        },
        bindEvent: function() {
          var t = this;
          l("#jArrivalnotice").on("click", "#jArrivalnoticeBtn", function() {
            var e, i = l(this);
            t.validateForm() && (e = l("#jAnForm").formParams(),
              globalObject.AJAX.jsonp({
                url: a.Goods.arrival,
                data: {
                  productId: t.btn.attr("data-product-id"),
                  mobile: e.mobile,
                  email: e.email
                }
              }, function(e) {
                t.defaultSetting.subCallBack(e),
                  globalObject.Dialog.ok("到货通知订阅成功！", t.btn),
                t._dialog && t._dialog.remove()
              }, function(t) {
                globalObject.Dialog.error(t._error.msg, i)
              }, i))
          })
        }
      },
      l.fn.arrivalNotice = function(t) {
        l(this).hasClass("btn-disabled") || (l.extend(s.defaultSetting, t),
          t = s,
          t.btn = l(this),
          t.init())
      }
  }),
  define("module/item/1.0.0/baseinfo/promotion/btn", ["require", "exports", "module", "pub/module/cart/1.0.0/add-cart", "lib/template/3.0/template", "module/common/cart/1.0.0/arrival-notice", "module/item/1.0.0/baseinfo/promotion/common", "lib/gallery/channel/1.0.0/channel"], function(t, e, i) {
    "use strict";
    var o, n, a, r, s = t("pub/module/cart/1.0.0/add-cart");
    t("lib/template/3.0/template");
    t("module/common/cart/1.0.0/arrival-notice"),
      o = t("module/item/1.0.0/baseinfo/promotion/common"),
      n = t("lib/gallery/channel/1.0.0/channel"),
      a = n.get("item/promotion"),
      r = {
        init: function() {
          var t = this;
          t.events(),
          o.cookie("_notice") && $(".jArrival").arrivalNotice(),
            t.initBtnStatus()
        },
        initBtnStatus: function() {
          var t = {
            isHidden: 800 == o.getCacheData("cate-id"),
            buyNowBtn: {
              bpm: "3.1.1.1.12." + o.gProductId
            },
            coverImg: o.getCacheData("cover-img"),
            className: "jAddCart",
            bpm: "3.1.1.1.3." + o.gProductId,
            productId: o.gProductId,
            html: "加入购物车"
          };
          o.exProData({
            btn: t
          })
        },
        tariffStatus: function() {
          var t = o.proInfoData
            , e = t.promotion
            , i = e.maxOrderAmount
            , n = t.price.showOriginalPrice ? t.price.originalPrice : t.price.scarePrice
            , a = i ? 1 * n > 1 * i : !1;
          $(".jFastBuy")[a ? "addClass" : "removeClass"]("btn-disabled")
        },
        types: {
          buyNowBtnStatus: function() {
            var t, e = o.proInfoData.btn.className, i = ["jLoginAddCart", "jAddCart"], n = !1;
            for (t = 0; t < i.length; t++)
              if (e.indexOf(i[t]) > -1) {
                n = !0;
                break
              }
            return {
              isShowBuyNowBtn: n
            }
          },
          noDelivery: {
            html: "不支持配送",
            className: "btn-disabled"
          },
          shelved: {
            html: "此商品已下架",
            className: "btn-disabled"
          },
          grabEnds: {
            className: "btn-disabled",
            html: "已抢完"
          },
          arrivalNotice: {
            className: "jArrival btn-arrival",
            bpm: "3.1.1.1.5." + o.gProductId,
            html: "到货通知"
          },
          timeRush: function() {
            var t = o.proInfoData.promotion
              , e = t.limitPanicBuyingDataModel
              , i = t.store
              , n = i > e.canBuyNum ? e.canBuyNum : i
              , a = o.proInfoData.btn;
            return t.supportDelivery && t.store > 0 && (e.pmtStore <= 0 ? e.xsqgTip && "" != e.xsqgTip ? (a.className = "btn-disabled",
              a.xsqgTip = e.xsqgTip) : (a.className = "btn-disabled",
              a.html = "已抢完") : 1 == e.buyType ? (a.bpm = "3.1.1.1.12." + o.gProductId,
              a.className = "jFastBuy btn-pink",
              a.html = "立即购买") : 0 >= n ? a.className = "btn-disabled" : (a.className = "jLoginAddCart",
              a.bpm = "3.1.1.1.13." + o.gProductId)),
              a
          },
          groupBuy: function() {
            var t = o.proInfoData.promotion
              , e = t.store
              , i = t.groupDataModel
              , n = o.proInfoData.btn;
            return n.className = "btn-disabled",
            e >= i.canBuyNum && 0 != i.canBuyNum && e > 0 && t.supportDelivery && i.pmtStore > 0 && (n.bpm = "3.1.1.1.17." + o.gProductId,
              n.className = "jFastBuy btn-pink"),
              1 === i.actType ? n.html = "立即参团" : 2 === i.actType && (n.html = "立即预定"),
            i.hasStart || (n.html = "即将开始..."),
            (!t.supportDelivery || i.pmtStore <= 0 || 0 == t.store) && (n.className = "btn-disabled",
              n.html = "已抢完",
              t.supportDelivery ? 0 == e && (n.className = "jArrival btn-arrival",
                n.bpm = "3.1.1.1.5." + o.gProductId,
                n.html = "到货通知") : n.html = "不支持配送"),
              n
          }
        },
        events: function() {
          var t = this;
          o.o.on("click", ".jAddCart", function(e) {
            t.addToCart($(this))
          }).on("click", ".jLoginAddCart", function() {
            var e = $(this);
            o.loginBox(function() {
              t.addToCart(e)
            })
          }).on("click", ".jFastBuy", function() {
            $(this).hasClass("btn-disabled") || t.setFastBuy($(this))
          }).on("click", ".jArrival", function() {
            $(this).arrivalNotice()
          })
        },
        addToCart: function(t) {
          var e = $("#jNumBox").find(".jQtyTxt");
          s.addCart({
            sender: t,
            data: {
              quantity: e.val()
            }
          })
        },
        setFastBuy: function(t) {
          var e = this
            , i = $("#jNumBox").find(".jQtyTxt");
          o.cookie("_nick") ? o.ajax("http://api.cart.yunhou.com/cart/fastBuy", {
            productId: o.gProductId,
            quantity: i.val()
          }, function(t) {
            var e = "http://cart.yunhou.com/buy-at-once.shtml";
            0 == t.deliveryType && (e = "http://cart.yunhou.com/buy-now.shtml"),
              location.href = e
          }) : o.loginBox(function() {
            e.setFastBuy(t)
          })
        },
        setData: function(t, e) {
          var i = r.types[t];
          o.exProData({
            btn: $.isFunction(i) ? i(e) : i
          })
        }
      },
      a.listen("info/loaded", function() {}),
      a.listen("qtyBox/tariffLimited", function(t) {
        $(".jFastBuy")[t ? "addClass" : "removeClass"]("btn-disabled")
      }),
      i.exports = r
  }),
  define("module/item/1.0.0/baseinfo/promotion/spike", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      types: {
        isHidden: {
          isHidden: !0
        },
        createItem: function() {
          var t = o.proInfoData.promotion;
          return {
            isSeckill: t.isSeckill,
            seckillUrl: t.seckillUrl
          }
        }
      }
    };
    i.exports = {
      setData: function(t) {
        var e = n.types[t];
        o.exProData({
          spike: $.isFunction(e) ? e() : e
        })
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/qty-box", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common", "lib/gallery/channel/1.0.0/channel", "lib/ui/box/1.0.1/box"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = t("lib/gallery/channel/1.0.0/channel")
      , a = n.get("item/promotion")
      , r = t("lib/ui/box/1.0.1/box")
      , s = {
      init: function() {
        this.events()
      },
      _customsQuotas: function(t, e) {
        var i, n, r = !0, s = $("#jTariffTips"), l = s.find(".jTariffBd"), c = e || 1, u = o.proInfoData, d = u.promotion, h = d.maxOrderAmount, m = u.price.showOriginalPrice ? u.price.originalPrice : u.price.scarePrice, p = c * m;
        d.customsInfo && d.customsInfo.tax ? d.customsInfo.tax : !1;
        return h && 0 != h.length && 1 * p > 1 * h && c > 1 ? (i = Math.floor(h / m) || 1,
          n = '抱歉您已超过海关限额<em class="ico-money">￥</em><span style="color:red;" class="jLimitMoney">' + h + "</span>,请分次购买.",
          a.fire("qtyBox/tariffLimited", !0),
          s.show(),
          l.html(n)) : (s.hide(),
          l.html(""),
          a.fire("qtyBox/tariffLimited", !1)),
          r
      },
      events: function() {
        var t = this;
        o.o.on("change", "#jNumBox .jQtyTxt", function() {
          var e = $(this)
            , i = Number(e.attr("data-max"))
            , o = Number(e.val())
            , n = e.attr("data-limit-num")
            , a = "库存有限，此商品最多只能购买" + i + "件";
          e.hasClass("btn-disabled") || (n && i > n && (a = "此商品限购" + n + "件"),
            isNaN(o) ? (e.val("1"),
              r.warn("必须为正整数", e[0])) : o > i ? (e.val("1"),
              r.warn(a, e[0])) : 0 >= o ? (e.val("1"),
              r.warn("此商品最小购买数量为1", e[0])) : t._customsQuotas(e, o))
        }),
          o.o.on("click", "#jNumBox .jQtyMin", function() {
            var e = $(this)
              , i = e.siblings(".jQtyTxt")
              , o = 1 * i.val();
            e.hasClass("btn-disabled") || (o--,
              o > 0 ? t._customsQuotas(i, o) && i.val(o) : r.warn("此商品最小购买数量为1", i[0]))
          }),
          o.o.on("click", "#jNumBox .jQtyAdd", function() {
            var e = $(this)
              , i = e.siblings(".jQtyTxt")
              , o = 1 * i.attr("data-max")
              , n = 1 * i.val()
              , a = i.attr("data-limit-num")
              , s = i.attr("data-limit-tips");
            if (!e.hasClass("btn-disabled"))
              return n++,
                a && n > a ? void r.warn(s, i[0]) : void (o >= n ? t._customsQuotas(i, n) && i.val(n) : r.warn("库存有限，此商品最多只能购买" + o + "件", i[0]))
          })
      },
      types: {
        isHidden: {
          isHidden: !0
        },
        inStock: function() {
          return {
            dataMax: o.proInfoData.promotion.store,
            stockMsg: "有货"
          }
        },
        soldOut: {
          isHidden: !0,
          isHiddenNumBox: !0,
          stockMsg: "库存不足"
        },
        timeRush: function() {
          var t = ""
            , e = ""
            , i = o.proInfoData.promotion
            , n = i.store
            , a = i.limitPanicBuyingDataModel
            , r = !1;
          return t = n > a.canBuyNum ? a.canBuyNum : n,
            e = "此商品限购" + t + "件",
          0 >= t && (e = "此商品限购" + a.limitValue + "件,您已无购买次数"),
            r = 0 == t,
          i.supportDelivery && i.store > 0 && a.pmtStore <= 0 && (r = !0),
          i.supportDelivery || (r = !0),
          {
            isHidden: r,
            limitNum: t,
            tips: e,
            limitTips: e
          }
        },
        groupBuy: function() {
          var t = ""
            , e = ""
            , i = ""
            , n = o.proInfoData.promotion
            , a = n.store
            , r = n.groupDataModel
            , s = !1;
          return a < r.canBuyNum ? (e = "来晚啦～～当前活动库存仅剩" + a + "件",
            t = a) : 0 == r.canBuyNum ? (e = "您已超过限购数，无法在购买",
            t = r.canBuyNum) : 0 >= a ? e = "库存不足" : n.supportDelivery ? r.pmtStore <= 0 ? e = "已抢完" : (t = r.canBuyNum,
            e = "您当前最多只可购买" + t + "件") : e = "不支持配送",
          (!n.supportDelivery || r.pmtStore <= 0 || 0 == n.store) && (s = !0),
            r.hasStart ? i = 0 >= t ? "每人限购" + r.numPerMember + "件，您已超过限购数" : "每个ID限购" + r.numPerMember + "件" : (i = "每个ID限购" + r.numPerMember + "件",
              e = "活动还未开始"),
          {
            isHidden: s,
            tips: i,
            limitNum: t,
            limitTips: e
          }
        }
      }
    };
    s.init(),
      a.listen("info/loaded", function() {
        s._customsQuotas()
      }),
      i.exports = {
        events: function() {
          s.events()
        },
        setData: function(t) {
          var e = s.types[t];
          o.exProData({
            qtyBox: $.isFunction(e) ? e() : e
          })
        }
      }
  }),
  define("module/item/1.0.0/baseinfo/promotion/preferential", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      init: function() {
        this.events()
      },
      getPreferentialStr: function(t) {
        var e, i, o, n, a, r, s;
        for (e = 0; e < t.length; e++)
          i = t[e],
            o = 0 != i.list.length,
            n = i.url,
            n = n && 0 != n.length ? n : !1,
            a = "",
            r = "",
            s = o ? "" : 'style="max-width:240px;"',
            n ? "mobile" == i.type ? (a = '<span class="txt-els a-txt" title="' + i.ad + '" ' + s + ">" + i.ad + "</span>",
              r = '<a href="javascript:;" class="a-icon jAppDown go-details" data-title="' + i.ad + '" data-src="' + n + '">app下载>></a>') : (a = '<a href="' + n + '" target="_blank" class="txt-els a-txt jItemTxt" title="' + i.ad + '" ' + s + ">" + i.ad + "</a>",
              r = '<a href="' + n + '" target="_blank" class="a-icon go-details">详情&gt;&gt;</a>') : a = '<span class="txt-els a-txt" title="' + i.ad + '" ' + s + ">" + i.ad + "</span>",
            i._labelStr = a + r,
            i._hasItemList = o;
        return t
      },
      events: function() {
        var t = this;
        o.o.on("click", ".jProArrow", function(t) {
          if ($(t.target).hasClass("jAppDown"))
            return !1;
          var e = $(".jPromItem").index($(this));
          $(this).toggleClass("prom-item-hover").siblings().removeClass("prom-item-hover"),
            o.resetImageLoader($(".jProImgBox").eq(e))
        }).on("click", ".jAppDown", function() {
          t.setPhoneApp($(this))
        })
      },
      setPhoneApp: function(t) {
        var e = t.attr("data-title")
          , i = t.attr("data-src")
          , n = ['<div style="text-align:center;">', "<p>" + e + "</p>", '<p style="padding:5px 0;"><img src="' + i + '" style="width:150px;"/></p>', "<p>扫二维码下APP立享手机专享价</p>", "</div>"]
          , a = {
          id: "jAppDownDialog",
          fixed: !0,
          skin: "mobile-dialog",
          content: n.join(""),
          button: [{
            value: "关闭"
          }]
        };
        o.dialog.create(a).showModal()
      },
      types: {
        isHidden: {
          isHidden: !0
        },
        createItem: function() {
          var t = o.proInfoData.promotion
            , e = t.hasProductActivity
            , i = t.promotionTagModelList
            , a = i && 0 != i.length;
          return {
            isHidden: !e,
            hasPromotion: a && t.hasProductActivity,
            promotionTagModelList: n.getPreferentialStr(i)
          }
        }
      }
    };
    i.exports = {
      init: function() {
        n.init()
      },
      setData: function(t) {
        var e = n.types[t];
        o.exProData({
          preferential: $.isFunction(e) ? e() : e
        })
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/price", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      init: function() {
        this.initPrice()
      },
      initPrice: function() {
        var t = o.getCacheData("original-price")
          , e = Number(t)
          , i = o.getCacheData("market-price")
          , n = Number(i)
          , a = e >= n || 0 == n
          , r = {
          showOriginalPrice: !0,
          isHiddenMarketPrice: a,
          originalPrice: t,
          largerPrice: e > n ? t : i,
          marketPrice: i
        };
        o.exProData({
          price: r
        })
      },
      types: {
        mkActivity: function(t) {
          var e = ["limitPromotionDataModel", "limitPanicBuyingDataModel", "groupDataModel"]
            , i = o.proInfoData.promotion
            , n = i[e[t]]
            , a = o.proInfoData.price.largerPrice
            , r = Number(n.price) >= Number(a);
          return o.setCacheData("scare-price", n.price),
          {
            showOriginalPrice: !1,
            isHidePrice: i.isHidePrice || r,
            scareText: n.text,
            scarePrice: n.price
          }
        }
      }
    };
    i.exports = {
      init: function() {
        n.init()
      },
      setData: function(t, e) {
        var i = n.types[t];
        o.exProData({
          price: $.isFunction(i) ? i(e) : i
        })
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/tariff", ["require", "exports", "module", "./common", "lib/ui/box/1.0.1/box", "lib/gallery/channel/1.0.0/channel", "lib/template/3.0/template"], function(t, e, i) {
    "use strict";
    var o = t("./common")
      , n = t("lib/ui/box/1.0.1/box")
      , a = t("lib/gallery/channel/1.0.0/channel")
      , r = a.get("item/promotion")
      , s = t("lib/template/3.0/template")
      , l = {
      init: function() {
        this._events()
      },
      _timerForEnter: !1,
      _timer: !1,
      _pop: function() {
        var t = this
          , e = $("#jTariffPopStr")
          , i = "";
        return 0 == e.length || 0 == $.trim(e.text()).length ? !1 : (i = $.trim(e.text()),
          void (t._detailPop = n.bubble('<div class="tariff-pop">' + i + '<a href="http://help.yunhou.com/g/guide-taxrate-description.html" target="_blank" style="margin-left:10px;">了解税率</a></div>', {
            id: "jTariffPop",
            showWithAni: "none",
            hideWithAni: "none",
            align: "b!",
            duration: 0
          }, $("#jShowTariffDetail")[0])))
      },
      setData: function(t) {
        var e = l._types[t];
        o.exProData({
          tariff: $.isFunction(e) ? e() : e
        })
      },
      getPrice: function() {
        var t, e = this, i = o.proInfoData, n = i.promotion, a = n.isCustoms ? "由卖家承担,若配送过程中产生税费,请联系客服" : n.taxDetail;
        o.gIsMarketable && n.isCustoms && n.supportDelivery ? (t = n.customsInfo,
          t ? e.renderTmpl({
            isShowTariff: t.isShowTax,
            price: t.tax,
            taxDetail: a,
            popHtml: t.customsRule
          }) : $("#jTariffBox").hide()) : e.renderTmpl({
          taxDetail: a
        })
      },
      renderTmpl: function(t) {
        $("#jTariffInfo").html(s("item/1.0.0/source/baseinfo/promotion/tariff/item", t || {}))
      },
      _types: {
        isHidden: {
          isHidden: !0
        }
      },
      _hide: function() {
        var t = this;
        t._timer = setTimeout(function() {
          t._detailPop && t._detailPop.hide(),
          t._timer && clearTimeout(t._timer)
        }, 300)
      },
      _events: function() {
        var t = this;
        o.o.on("mouseenter", "#jShowTariffDetail", function(e) {
          t._timer && clearTimeout(t._timer),
            t._timerForEnter = setTimeout(function() {
              t._pop()
            }, 100)
        }).on("mouseleave", "#jShowTariffDetail", function(e) {
          t._timerForEnter && clearTimeout(t._timerForEnter),
            t._hide()
        }),
          $("body").on("mouseenter", "#jTariffPop", function() {
            t._timer && clearTimeout(t._timer)
          }).on("mouseleave", "#jTariffPop", function() {
            t._hide()
          })
      }
    };
    r.listen("info/loaded", function() {
      l.getPrice()
    }),
      i.exports = l
  }),
  define("module/item/1.0.0/baseinfo/promotion/evaluation", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      init: function() {
        this.initEvalution(),
          this.events()
      },
      initEvalution: function() {
        var t = o.getCacheData("evaluation")
          , e = {
          isHidden: !t || 0 == t,
          num: t
        };
        o.exProData({
          evaluation: e
        })
      },
      events: function() {
        o.o.on("click", "#jBtnCmdList", function() {
          $(".jNavForComment").click()
        })
      }
    };
    i.exports = {
      init: function() {
        n.init()
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/specification", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = {
      types: {
        isHidden: {
          isHidden: !0
        }
      }
    };
    i.exports = {
      setData: function(t) {
        var e = n.types[t];
        o.exProData({
          specification: $.isFunction(e) ? e() : e
        })
      }
    }
  }),
  define("module/common/countDown", ["require", "exports", "module", "jquery", "pub/js/core/bbg", "lib/core/1.0.0/io/request"], function(t, e, i) {
    "use strict";
    function o(t) {
      this.defaultSetting = n.extend(this, this.defaultSetting, t || {}),
        this.init()
    }
    var n = t("jquery")
      , a = (t("pub/js/core/bbg"),
      t("lib/core/1.0.0/io/request"));
    return o.prototype = {
      defaultSetting: {
        url: "",
        currentTime: null ,
        labelCtn: "b",
        isShowArea: !1,
        targetTime: null ,
        isShowTimeText: !0,
        timeText: ["天", "时", "分", "秒", "毫秒"],
        textLabel: "em",
        type: {
          d: !1,
          h: !0,
          m: !0,
          s: !0,
          ms: !1
        },
        callback: function() {},
        container: null
      },
      init: function(t) {
        var e = this;
        e.ShowTimeText(),
          e.getServiceTime(function(t) {
            n(e.container).each(function(i) {
              n(this).attr("data-startTime", t),
                e._countDown(n(this))
            })
          })
      },
      ShowTimeText: function() {
        this.timeText = this.isShowTimeText ? this.timeText : ["", "", "", "", ""]
      },
      ajax: function(t, e, i, o) {
        var r = {
          url: t,
          data: n.extend({
            platform: "js"
          }, e)
        };
        a.jsonp(r, function(t) {
          i && i(t)
        }, function(t) {
          o && o(t)
        })
      },
      getServiceTime: function(t) {
        "" != this.url ? this.ajax(this.url, {}, function(e) {
          e && null  != e && t && t(e)
        }, function() {}) : t && t()
      },
      createTextLabel: function(t) {
        var e = this
          , i = e.timeText[t];
        return "" != e.textLabel && "" != i && (i = "<" + e.textLabel + ">" + e.timeText[t] + "</" + e.textLabel + ">"),
          i
      },
      _countDown: function(t) {
        function e(t) {
          var t = parseInt(t, 10);
          return t > 0 ? (9 >= t && (t = "0" + t),
            String(t)) : "00"
        }
        function i(t, i, n, a, r) {
          var s, u = "", d = 0, h = [i, n, a, r];
          l.type.d ? u += o(t, l.type.d) + l.createTextLabel(0) : h[0] = e(1 * i + 24 * t);
          for (s in l.type)
            l.type[s] && "d" != s && (d++,
              u += o(h[d - 1], l.type[s]) + l.createTextLabel(d));
          c.html(u)
        }
        function o(t, e) {
          var i, o = "", n = "";
          if (e && e.length > 0 && (o = ' class="' + e + '"'),
            t.length > 1 && l.isShowArea)
            for (i = 0; i < t.length; i++)
              n += "<" + l.labelCtn + o + "><i></i>" + t.slice(i, i + 1) + "</" + l.labelCtn + ">";
          else
            n = "<" + l.labelCtn + o + ">" + t + "</" + l.labelCtn + ">";
          return n
        }
        function n() {
          var o, n, c, u, d;
          h += 100,
            r += 100,
          r != (new Date).getTime() && (h += (new Date).getTime() - r,
            r = (new Date).getTime()),
            o = (m - h) / 1e3,
            o > 0 ? (n = e(o % 60),
              c = Math.floor(o / 60) > 0 ? e(Math.floor(o / 60) % 60) : "00",
              u = Math.floor(o / 3600) > 0 ? e(Math.floor(o / 3600) % 24) : "00",
              d = Math.floor(o / 86400) > 0 ? e(Math.floor(o / 86400)) : "00",
              i(d, u, c, n, a),
              0 == a ? a = 9 : a--) : (i("00", "00", "00", "00", "0"),
              l.callback(t),
              clearInterval(s))
        }
        var a, r, s, l = this, c = t, u = c.attr("data-endTime"), d = c.attr("data-startTime"), h = l.currentTime, m = l.targetTime;
        return m = u ? u : m,
          h = d ? d : h,
          null  == h || null  == m ? !1 : (h = parseInt(h),
            m = parseInt(m),
            a = 9,
            r = (new Date).getTime(),
            void (s = setInterval(n, 100)))
      }
    },
      function(t) {
        new o(t)
      }
  }),
  define("module/item/1.0.0/baseinfo/promotion/count-down", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common", "module/common/countDown"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = t("module/common/countDown")
      , a = {
      getLimitSale: function() {
        return {
          startTime: this.promotionModel && this.promotionModel.currentTime || null ,
          endTime: this.promotionModel && this.promotionModel.limitTimeEnd || null
        }
      },
      getCountDown: function() {
        var t, e = this, i = $("#jScareBuyingCountDown"), o = i.attr("data-startTime"), a = i.attr("data-endTime"), r = i.attr("data-time-text");
        r = r && r.indexOf(",") > -1 ? r.split(",") : ["天", "时", "分", "秒结束"],
          t = i.attr("data-type"),
          t = t || {
              d: !0,
              h: !0,
              m: !0,
              s: !0
            },
          n({
            container: i,
            currentTime: o,
            targetTime: a,
            type: t,
            timeText: r,
            callback: function() {
              e.emit("afterCountDown")
            }
          })
      },
      types: {
        active: function() {
          var t = o.proInfoData.promotion
            , e = t.goodsLevelType
            , i = !(!e || 0 == $.trim(e).length);
          return {
            isHidden: !i
          }
        },
        isHidden: {
          isHidden: !0
        },
        timeRush: function() {
          var t = o.proInfoData.promotion
            , e = t.limitPanicBuyingDataModel;
          return {
            startTime: e.currentTime,
            endTime: e.limitTimeEnd
          }
        },
        timePromotion: function() {
          var t = o.proInfoData.promotion
            , e = t.limitPromotionDataModel;
          return {
            startTime: e.currentTime,
            endTime: e.limitTimeEnd
          }
        },
        groupBuy: function() {
          var t = o.proInfoData.promotion
            , e = t.groupDataModel
            , i = e.limitTimeEnd
            , n = e.currentTime
            , a = ""
            , r = ""
            , s = !1
            , l = "<span>" + e.hasSaleNum + "</span>人已购买";
          return 1 === e.actType ? s = "团购" : 2 === e.actType && (s = "预售"),
            e.hasStart ? l = "<span>" + e.hasSaleNum + "</span>人已购买" : (i = e.limitTimeStart,
              a = "天,时,分,秒开始",
              l = "即将开始..."),
            864e5 > i - n ? r = {
              d: !1,
              h: !0,
              m: !0,
              s: !0
            } : 36e5 > i - n && (r = {
              d: !1,
              h: !1,
              m: !0,
              s: !0
            }),
          {
            type: r,
            timeText: a,
            buyLabel: s,
            rightText: l,
            startTime: e.currentTime,
            endTime: i
          }
        }
      }
    };
    o.Emitter.applyTo(a),
      i.exports = {
        on: function(t, e) {
          a.on(t, e)
        },
        getCountDown: function() {
          a.getCountDown()
        },
        setData: function(t) {
          var e = a.types[t];
          o.exProData({
            countDown: $.isFunction(e) ? e() : e
          })
        }
      }
  }),
  define("module/item/1.0.0/baseinfo/promotion/market-activity", ["require", "exports", "module", "module/item/1.0.0/baseinfo/promotion/common", "module/item/1.0.0/baseinfo/promotion/delivery", "module/item/1.0.0/baseinfo/promotion/btn", "module/item/1.0.0/baseinfo/promotion/qty-box", "module/item/1.0.0/baseinfo/promotion/price", "module/item/1.0.0/baseinfo/promotion/count-down"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/baseinfo/promotion/common")
      , n = t("module/item/1.0.0/baseinfo/promotion/delivery")
      , a = t("module/item/1.0.0/baseinfo/promotion/btn")
      , r = t("module/item/1.0.0/baseinfo/promotion/qty-box")
      , s = t("module/item/1.0.0/baseinfo/promotion/price")
      , l = t("module/item/1.0.0/baseinfo/promotion/count-down")
      , c = {
      init: function() {
        var t = this;
        t.setMarketActivity()
      },
      setMarketActivity: function() {
        var t = this
          , e = o.proInfoData.promotion
          , i = e.goodsLevelType;
        i && 0 != $.trim(i).length && ("limit_time_promotion" == i ? t.timePromotion() : "limit_time_rush" == i ? t.timeRush() : "group_buy" == i && t.groupBuy())
      },
      timePromotion: function() {
        s.setData("mkActivity", "0"),
          l.setData("isHidden")
      },
      timeRush: function() {
        r.setData("timeRush"),
          a.setData("timeRush"),
          s.setData("mkActivity", "1"),
          l.setData("isHidden")
      },
      groupBuy: function() {
        l.setData("groupBuy"),
          a.setData("groupBuy"),
          r.setData("groupBuy"),
          n.setData("groupBuy"),
          s.setData("mkActivity", "2")
      }
    };
    i.exports = {
      init: function() {
        c.init()
      }
    }
  }),
  define("module/item/1.0.0/baseinfo/promotion/info", ["require", "exports", "module", "jquery", "bbg", "lib/template/3.0/template", "lib/gallery/channel/1.0.0/channel", "module/item/1.0.0/baseinfo/promotion/common", "module/item/1.0.0/baseinfo/promotion/shop-info", "module/item/1.0.0/baseinfo/promotion/delivery", "module/item/1.0.0/baseinfo/promotion/btn", "module/item/1.0.0/baseinfo/promotion/spike", "module/item/1.0.0/baseinfo/promotion/qty-box", "module/item/1.0.0/baseinfo/promotion/preferential", "module/item/1.0.0/baseinfo/promotion/price", "./tariff", "module/item/1.0.0/baseinfo/promotion/evaluation", "module/item/1.0.0/baseinfo/promotion/specification", "module/item/1.0.0/baseinfo/promotion/count-down", "module/item/1.0.0/baseinfo/promotion/market-activity"], function(t, e, i) {
    "use strict";
    var o, n, a, r, s, l, c, u, d, h, m, p, f, g, v, b = t("jquery"), y = (t("bbg"),
      t("lib/template/3.0/template")), x = t("lib/gallery/channel/1.0.0/channel");
    x.define("item/promotion", ["statistics/init", "qtyBox/tariffLimited", "info/loaded", "info/dataLoaded", "com/dataLoaded"]),
      o = x.get("item/promotion"),
      n = t("module/item/1.0.0/baseinfo/promotion/common"),
      a = t("module/item/1.0.0/baseinfo/promotion/shop-info"),
      r = t("module/item/1.0.0/baseinfo/promotion/delivery"),
      s = t("module/item/1.0.0/baseinfo/promotion/btn"),
      l = t("module/item/1.0.0/baseinfo/promotion/spike"),
      c = t("module/item/1.0.0/baseinfo/promotion/qty-box"),
      u = t("module/item/1.0.0/baseinfo/promotion/preferential"),
      d = t("module/item/1.0.0/baseinfo/promotion/price"),
      h = t("./tariff"),
      m = t("module/item/1.0.0/baseinfo/promotion/evaluation"),
      p = t("module/item/1.0.0/baseinfo/promotion/specification"),
      f = t("module/item/1.0.0/baseinfo/promotion/count-down"),
      g = t("module/item/1.0.0/baseinfo/promotion/market-activity"),
      f.on("afterCountDown", function() {
        location.reload()
      }),
      v = {
        opt: {},
        _isFirstLoad: !0,
        init: function() {
          var t = this;
          t.initMod(),
            b.extend(this, this.opt),
            n.getAllData(function(e) {
              n.gIsMarketable ? t.inSales(e) : t.shelves(e)
            })
        },
        initMod: function() {
          s.init(),
            m.init(),
            u.init(),
            d.init(),
            h.init()
        },
        events: function() {
          return this._isFirstLoad ? void n.o.data("initData", n.proInfoData) : !1
        },
        shelves: function(t) {
          f.setData("isHidden"),
            h.setData("isHidden"),
            u.setData("isHidden"),
            a.setData("getShopStr"),
            s.setData("shelved"),
            c.setData("isHidden"),
            l.setData("isHidden"),
            o.fire("info/dataLoaded")
        },
        inSales: function(t) {
          var e = this;
          t.supportDelivery ? e.setStock(t) : (r.setData("nonDelivery"),
            s.setData("noDelivery"),
            c.setData("isHidden"),
            h.setData("isHidden")),
            f.setData("active"),
            a.setData("getShopStr"),
            u.setData("createItem"),
            l.setData("createItem"),
            g.init(),
            o.fire("info/dataLoaded")
        },
        setStock: function(t) {
          t.store > 0 ? c.setData("inStock") : (s.setData("arrivalNotice"),
            c.setData("soldOut"))
        },
        setStatistics: function(t) {
          var e = n.proInfoData.price.scarePrice;
          x.get("item/promotion").fire("statistics/init", {
            favouriteCount: n.getCacheData("favourite-count"),
            store: t && t.store || 0,
            price: e || 0
          })
        },
        createProInfo: function() {
          s.setData("buyNowBtnStatus");
          var t = y.render("jProInfoTmpl", n.proInfoData);
          b("#jProInfo").html(t)
        }
      },
      n.Emitter.applyTo(v),
      o.listen("com/dataLoaded", function(t) {
        v.createProInfo(),
          v.events(),
          v.emit("loaded", {}),
          v.setStatistics(t),
          v._isFirstLoad = !1,
        n.gIsMarketable && f.getCountDown(),
          o.fire("info/loaded")
      }),
      i.exports = {
        on: function(t, e) {
          v.on(t, e)
        },
        init: function() {
          v.init()
        }
      }
  }),
  define("module/item/1.0.0/common/share", ["require", "exports", "module", "pub/module/ajax/1.0.0/ajax", "lib/core/1.0.0/io/cookie"], function(t, e, i) {
    "use strict";
    function o(t) {
      this.defaultSetting = $.extend(this, this.defaultSetting, t || {}),
        this.init()
    }
    var n = t("pub/module/ajax/1.0.0/ajax")
      , a = t("lib/core/1.0.0/io/cookie");
    return o.prototype = {
      defaultSetting: {
        title: document.title,
        url: document.URL,
        pic: "",
        target: "_blank",
        shareSelector: {
          qzone: ".jShareQzone",
          sina: ".jShareSina",
          qq: ".jShareQQ",
          renren: ".jShareRenRen"
        }
      },
      init: function() {
        var t = this;
        t.createHref(),
          t.bindEvent()
      },
      getTitle: function() {
        var t = this
          , e = $("[data-share-title]")
          , i = e.attr("data-share-title")
          , o = t.title;
        return 0 != e.length && 0 != $.trim(i).length && null  != i && (o = $.trim(i)),
          o
      },
      getDesc: function() {
        var t, e = document.getElementsByTagName("meta"), i = "";
        for (t in e)
          e[t] && "undefined" != typeof e[t].name && "description" == e[t].name.toLowerCase() && (i = e[t].content);
        return i
      },
      urlAr: function() {
        return {
          qzone: "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=",
          sina: "http://v.t.sina.com.cn/share/share.php?url=",
          qq: "http://connect.qq.com/widget/shareqq/index.html?url=",
          renren: "http://widget.renren.com/dialog/share?resourceUrl="
        }
      },
      createHref: function(t) {
        var e = this;
        t ? e.url = t : "",
          $.each(e.shareSelector, function(t, i) {
            var o = e.urlAr()[t] + encodeURIComponent(e.url) + "&title=" + encodeURIComponent(e.title) + "&pic=" + encodeURIComponent(e.pic);
            "qzone" == t ? (o = o.replace(/\&pic/g, "&pics"),
              o += "&desc=" + e.getDesc()) : "renren" == t && (o += "&srcUrl=" + e.url,
              o += "&description=" + e.getDesc()),
              $(i).attr({
                href: o,
                target: e.target
              })
          }),
          a.get("_nick") ? $(e.shareSelector.qq).parents("div.share-div").addClass("share-money") : ""
      },
      bindEvent: function() {
        var t = this
          , e = $(t.shareSelector.qq).parents("div.share-div");
        e.delegate("a", "click", function() {
          var i = $(this);
          return a.get("_nick") && !e.hasClass("disabled") && gProductId && gProductId > 0 ? (n.jsonp({
            url: "http://api.mall.yunhou.com/Union/getTkUrl",
            data: {
              url: document.URL.split("?")[0]
            }
          }, function(e) {
            $(t.shareSelector.qq).parents("div.share-div").addClass("share-money").addClass("disabled"),
            e && t.createHref(e.url),
              i[0].click()
          }),
            !1) : void 0
        })
      }
    },
      function(t) {
        new o(t)
      }
  }),
  define("plugins/jquery.previewPicture", ["require", "exports", "module", "jquery", "lib/gallery/utils/1.0.0/image"], function(t, e, i) {
    var o = t("jquery")
      , n = t("lib/gallery/utils/1.0.0/image");
    o.fn.previewPicture = function(t) {
      function e() {
        C.html(""),
          u = o("<div />", {
            "class": "prev-box"
          }),
          d = o("<div />", {
            "class": "pic-big",
            html: N + z
          }),
          h = o("<div />", {
            "class": "pic-mid",
            html: z + '<div class="prev-drag-box"><div class="prev-drag"></div>' + N + " </div>"
          }),
          m = o("<div />", {
            "class": "pic-pager"
          }),
          p = o("<div />", {
            "class": "pic-list-box"
          }),
          f = o("<a />", {
            "class": "pager-btn pager-btn-pre pager-btn-disabled icon iconfont",
            href: "javascript:;",
            html: "&#xe602;"
          }),
          g = o("<a />", {
            "class": "pager-btn pager-btn-next icon iconfont",
            href: "javascript:;",
            html: "&#xe603;"
          }),
          p.html(G.getPagerHtml()),
          m.append(p),
        j.data.length > 5 && (m.append(f),
          m.append(g)),
          u.append(d),
          u.append(h),
          u.append(m),
          C.append(u)
      }
      function i() {
        return j.data && j.data.length <= 0 ? void C.html(H) : (e(),
          c = p.find(".pic-list"),
          l = c.find("a"),
          v = h.find(".prev-loading"),
          b = d.find(".prev-loading"),
          y = h.find(".img-error"),
          x = d.find(".img-error"),
          w = h.find(".prev-drag-box"),
          _ = w.find(".prev-drag"),
          s = p.width(),
          F = parseInt((s + j.marginRight) / P),
          q.w = d.width(),
          q.h = d.height(),
          L.w = h.width(),
          L.h = h.height(),
          G.init(),
          void O.init())
      }
      var a, r, s, l, c, u, d, h, m, p, f, g, v, b, y, x, w, _, k = {
        data: new Array,
        blankImg: "//s1.bbgstatic.com/pub/img/blank.gif",
        loadingImg: "//s3.bbgstatic.com/pub/img/loading/loading.gif",
        minWidth: 62,
        marginRight: 12,
        delay: 200
      }, j = o.extend(k, t), C = this, P = j.minWidth + j.marginRight, I = 0, D = !0, T = 0, B = 0, F = 0, q = {
        w: 0,
        h: 0
      }, S = {
        w: 0,
        h: 0
      }, L = {
        w: 0,
        h: 0
      }, $ = {
        w: 0,
        h: 0
      }, M = {
        w: 0,
        h: 0
      }, E = {
        top: 0,
        left: 0
      }, A = {
        top: 0,
        left: 0
      }, N = '<img class="img-error" src="' + j.blankImg + '" /> ', z = '<div class="prev-loading">正在努力加载,请稍后...</div>', H = '<div class="prev-empty">抱歉,无图片数据~</div>', R = {
        setPosition: function(t) {
          var e = t.pageX
            , i = t.pageY
            , o = i - E.top
            , n = e - E.left;
          E = w.offset(),
            A.top = o - M.h / 2,
            A.left = n - M.w / 2,
          0 > A.top && (A.top = 0),
          0 > A.left && (A.left = 0),
          A.top + M.h > $.h && (A.top = $.h - M.h),
          A.left + M.w > $.w && (A.left = $.w - M.w),
          A.top < 0 && (A.top = 0),
          A.left < 0 && (A.left = 0),
            _.css({
              top: A.top,
              left: A.left
            }),
            x.css({
              top: -A.top * (S.h / $.h),
              left: -A.left * (S.w / $.w)
            })
        },
        showBig: function() {
          d.show(),
            w.css({
              cursor: "move"
            })
        },
        hideBig: function() {
          d.hide(),
            _.hide(),
            w.css({
              cursor: "auto"
            })
        },
        showMidPic: function() {
          v.show();
          var t = j.data[B].midImg;
          D = !0,
            n.load(t, function(e) {
              D = !1,
                $.w = e.width,
                $.h = e.height,
                $.h > $.w ? $.h > L.h && ($.h = L.h,
                  $.w = L.h * $.w / $.h) : $.w > L.w && ($.w = L.w,
                  $.h = L.h * $.w / L.w);
              var i = {
                top: (L.h - $.h) / 2,
                left: (L.w - $.w) / 2,
                width: $.w,
                height: $.h,
                "line-height": $.h + "px"
              };
              w.css(i),
                y.css({
                  "max-width": $.w,
                  "max-height": $.h,
                  "vertical-align": "middle"
                }),
                E = w.offset(),
                y.attr("src", t),
                v.hide()
            }, function() {
              D = !0
            })
        },
        setPrevImgPosition: function(t) {
          S.w > q.w || S.h > q.h ? (x.css({
            top: 0,
            left: 0
          }),
          t && R.setPosition(t)) : (x.css({
            top: (q.h - S.h) / 2,
            left: (q.w - S.w) / 2
          }),
            w.css({
              cursor: "pointer"
            }))
        }
      }, G = {
        init: function() {
          G.setMinPic(),
            G.go()
        },
        getPagerHtml: function() {
          var t, e, i, o = j.data.length;
          for (r = P * o,
                 t = '<div class="pic-list clearfix" style="width:' + r + 'px;">',
                 e = "",
                 i = 0; o > i; i++)
            e = "",
            0 === i && (e = ' class="current" '),
              t += '<a href="javascript:;"' + e + '><em></em><img class="img-error" src="' + j.data[i].minImg + '" /> </a>';
          return t + "</div>"
        },
        go: function() {
          c.animate({
            left: -T
          }),
            G.setBtnStatus()
        },
        setBtnStatus: function() {
          0 == T ? f.addClass("pager-btn-disabled") : f.removeClass("pager-btn-disabled"),
            T >= r - s - j.marginRight || s >= r ? g.addClass("pager-btn-disabled") : g.removeClass("pager-btn-disabled")
        },
        curPicIsInPicListBox: function() {
          var t = B * P;
          return t >= T && T + s >= t
        },
        setMinPic: function() {
          l.removeClass("current"),
            l.eq(B).addClass("current"),
            R.showMidPic()
        }
      }, O = {
        init: function() {
          O.regBtnPre(),
            O.regBtnNext(),
            O.regMinPic(),
            O.regMidPic(),
            O.regDrag()
        },
        regBtnPre: function() {
          f.click(function() {
            T > 0 && (T -= P,
              G.go()),
            G.curPicIsInPicListBox() || (B--,
              G.setMinPic())
          })
        },
        regBtnNext: function() {
          g.click(function() {
            return o(this).hasClass("pager-btn-disabled") ? !1 : (r > T + s + P && (T += P,
              G.go()),
              void (G.curPicIsInPicListBox() || (B++,
                G.setMinPic())))
          })
        },
        regMinPic: function() {
          l.click(function() {
            return o(this).hasClass("pager-btn-disabled") ? !1 : (B = l.index(o(this)),
              void G.setMinPic())
          })
        },
        regMidPic: function() {
          h.hover(function() {
            I = j.delay,
              a = setInterval(function() {
                if (I -= 100,
                  0 >= I) {
                  clearInterval(a);
                  var t = j.data[B].bigImg;
                  n.load(t, function(e) {
                    return D ? void R.hideBig() : (S.w = e.width,
                      S.h = e.height,
                      $.w >= S.w && $.h >= S.h ? void R.hideBig() : (M.w = L.w * q.w / S.w,
                        M.h = L.h * q.h / S.h,
                        S.w < q.w || S.h < q.h ? (_.css({
                          display: "none"
                        }),
                          void R.hideBig()) : (_.css({
                          display: "block"
                        }),
                          R.showBig(),
                          _.css({
                            width: M.w,
                            height: M.h
                          }),
                          x.attr("src", t),
                          R.showBig(),
                          R.setPrevImgPosition(),
                          void b.hide())))
                  })
                }
              }, 100)
          }, function() {
            a && clearInterval(a),
              R.hideBig()
          })
        },
        regDrag: function() {
          h.mousemove(function(t) {
            R.setPrevImgPosition(t)
          })
        }
      };
      i()
    }
  }),
  define("module/item/1.0.0/baseinfo/preview-and-share", ["require", "exports", "module", "jquery", "module/item/1.0.0/common/share", "module/item/1.0.0/common/common", "plugins/jquery.previewPicture"], function(t, e, i) {
    "use strict";
    var o, n = t("jquery"), a = t("module/item/1.0.0/common/share"), r = t("module/item/1.0.0/common/common");
    t("plugins/jquery.previewPicture"),
      o = {
        init: function() {
          var t = this;
          n.extend(this, this.opt),
            t.goodsPreview()
        },
        goodsPreview: function() {
          n("#jGoodsPreview").previewPicture({
            data: r.getCacheJson("imageData")
          })
        },
        share: function() {
          var t = "云猴网 "
            , e = r.getCacheData("share-title")
            , i = r.getCacheData("scare-price")
            , o = i ? i : r.getCacheData("original-price");
          0 != n.trim(e).length && null  != e && (t = "#海购就去云猴全球购# "),
            a({
              title: t + n("#jGoodsH1").text() + " ¥" + o + " ，分享给大家！",
              pic: r.getCacheData("cover-img")
            })
        }
      },
      o.init(),
      r.Emitter.applyTo(o),
      i.exports = {
        share: function() {
          o.share()
        }
      }
  }),
  define("module/item/1.0.0/baseinfo/collect", ["require", "exports", "module", "module/item/1.0.0/common/common", "lib/ui/box/1.0.0/base"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/common/common")
      , n = (t("lib/ui/box/1.0.0/base"),
    {
      opt: {
        moduleId: "jColGoods"
      },
      init: function() {
        var t = this;
        $.extend(this, this.opt),
          t.o = o.o.find("#" + t.moduleId),
          t.setBtnStatus(),
          t.events()
      },
      changeIcon: function(t) {
        var e = ["收藏", "已收藏"];
        this.o[t ? "addClass" : "removeClass"]("btn-disabled").find(".jHeartTxt").text(e[t]).end().find(".jHeart").hide().eq(t).show()
      },
      setBtnStatus: function() {
        var t = this;
        o.ajax("http://item.yunhou.com/index/ajaxGetFavouriteData", {
          product_id: o.gProductId
        }, function(e) {
          t.changeIcon(e.isFavourite ? 1 : 0),
            $("#jCacheData").attr("data-favourite-count", e.favouriteCount),
            t.emit("changeCollectBtnStatus")
        })
      },
      addFavorite: function(t) {
        var e = this
          , i = e.o.hasClass("btn-disabled");
        i || (o.cookie("_nick") ? !i && o.ajax("http://api.mall.yunhou.com/product/addfavorite", {
          productId: o.gProductId,
          goodsId: o.gGoodsId
        }, function() {
          e.changeIcon(1),
            e.emit("addFavorite")
        }) : o.loginBox(function() {
          e.addFavorite(t)
        }))
      },
      events: function() {
        var t = this;
        o.o.on("click", "#" + t.moduleId, function() {
          var e = $(this);
          t.addFavorite(e)
        })
      }
    });
    o.Emitter.applyTo(n),
      i.exports = n
  }),
  define("module/item/1.0.0/proinfo/anchor", ["require", "exports", "module", "jquery", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o = t("jquery")
      , n = t("module/item/1.0.0/common/common")
      , a = {
      init: function() {
        var t = this;
        t.event()
      },
      isSelectedTab: !1,
      event: function() {
        var t = this;
        o("#jCutmain").on("click", ".jTabTitle", function() {
          t.isSelectedTab = !1;
          var e = o(this).attr("data-type");
          e && (window.location.hash = "gd-" + e),
            a.emit("beforeTabClick"),
            t.goAnchor(this),
            t.tabs(this)
        }),
          o(window).scroll(function() {
            t.scroll(),
            t.isSelectedTab && t.selectedTab()
          })
      },
      tabs: function(t) {
        o(t).addClass("hover").siblings().removeClass("hover")
      },
      selectedTab: function() {
        var t = this
          , e = Number(o("#jCutmain").offset().top);
        setTimeout(function() {
          o(".jTabCntBox:visible").each(function() {
            var i, n = o(this), a = Number(o(window).scrollTop()), r = Number(n.offset().top), s = Number(n.height());
            return e >= a ? (t.tabs(o(".jTabTitle:visible").eq(0).get()),
              !1) : a >= r && r + s > a ? (i = o(".jTabCntBox:visible").index(n),
              t.tabs(o(".jTabTitle:visible").eq(i).get()),
              !1) : void 0
          })
        }, 0)
      },
      scroll: function() {
        var t = o("#jCutmain").offset().top
          , e = o(window).scrollTop()
          , i = o(".jAnchorNav");
        e >= t ? i.css({
          position: "fixed",
          top: "0px",
          "z-index": 15
        }).find(".buys-info").show() : i.removeAttr("style").find(".buys-info").hide()
      },
      goAnchor: function(t) {
        var e = o(t).attr("data-type")
          , i = o("#gd-" + e).offset().top
          , n = this;
        o("html, body").stop(!0, !0).animate({
          scrollTop: i
        }, function() {
          n.isSelectedTab = !0,
            a.emit("afterTabClick")
        })
      }
    };
    n.Emitter.applyTo(a),
      i.exports = {
        on: function(t, e) {
          a.on(t, e)
        },
        init: function() {
          a.init()
        },
        setSelectedTab: function() {
          a.isSelectedTab = !0
        }
      }
  }),
  define("module/item/1.0.0/proinfo/imgload", ["require", "exports", "module", "jquery", "lib/gallery/utils/1.0.0/image", "bbg", "lib/gallery/widget/lazyload/1.0.0/lazyload", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o, n, a, r = t("jquery"), s = t("lib/gallery/utils/1.0.0/image");
    t("bbg"),
      o = t("lib/gallery/widget/lazyload/1.0.0/lazyload"),
      n = t("module/item/1.0.0/common/common"),
      a = {
        dom: {
          $detailCnt: r("#jGoodsDetailCnt"),
          $detailCntData: r("#jGoodsDetailCntData")
        },
        init: function() {
          var t = this;
          t.loadGoodsDetail()
        },
        loadGoodsDetail: function() {
          var t, e = this, i = r("<div>" + e.dom.$detailCntData.val() + "</div>"), n = i.find("img");
          n.each(function() {
            var t, e, i = r(this), o = i.attr("src"), n = s.parseSize(o), a = null , l = null , c = /[_](\d+)[x](\d+)[.](jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF)/, u = c.exec(o);
            u && 0 != u.length && (a = u[1],
              l = u[2]),
              i.attr("src", "//static5.bubugao.com/public/img/blank.gif"),
              i.attr("data-url", o),
              i.removeClass("jImg"),
              i.attr("src", BBG.URL.Img.blank),
            n && (t = n.width,
              e = n.height,
            t > 750 && (t = 750),
              i.css({
                "max-width": t,
                width: a,
                height: l,
                "max-height": n.height * t / n.width
              })),
              i.addClass("jIntroImg img-error")
          }),
            e.dom.$detailCnt.html(i),
            t = new o("img.jIntroImg",{
              effect: "fadeIn",
              dataAttribute: "url"
            }),
            a.emit("loaded")
        }
      },
      n.Emitter.applyTo(a),
      i.exports = {
        on: function(t, e) {
          a.on(t, e)
        },
        init: function() {
          a.init()
        }
      }
  }),
  define("module/item/1.0.0/common/jquery.pager", ["require", "jquery", "lib/core/1.0.0/io/request"], function(t) {
    var e = t("jquery")
      , i = t("lib/core/1.0.0/io/request");
    e.fn.pager = function(t) {
      var o, n = {
        nextHtml: "下一页",
        prevHtml: "上一页",
        clickPageFun: function() {},
        range: 5,
        isTotal: !0,
        curPage: 1,
        pageSize: 15,
        totalCount: 0,
        ajax: null ,
        paged: function(t, e) {}
      }, a = e.extend({}, n, t || {});
      $this = e(this),
        gIsLoading = !1,
        gCurPage = a.curPage,
        gTotalCount = a.totalCount,
        gTotalPage = 0,
        o = {
          init: function() {
            o.go(gCurPage),
              o.initEvent()
          },
          getTotalPage: function() {
            var t = 0;
            gTotalCount > 0 && (t = parseInt(gTotalCount / a.pageSize),
            gTotalCount % a.pageSize > 0 && t++),
              gTotalPage = t
          },
          getPagerCnt: function() {
            return e("<div />", {
              "class": "pager"
            })
          },
          getPager: function(t) {
            return t ? '<a href="javascript:;" class="pager-item" data-page=' + t + ">" + t + "</a>" : ""
          },
          getMore: function() {
            return "<span>...</span>"
          },
          getNext: function() {
            return a.nextHtml ? '<a href="javascript:;" class="pager-next">' + a.nextHtml + "</a>" : ""
          },
          getPrev: function() {
            return a.prevHtml ? '<a href="javascript:;" class="pager-prev">' + a.prevHtml + "</a>" : ""
          },
          getCurPager: function(t) {
            return t ? "<b>" + t + "</b>" : ""
          },
          getTotal: function() {
            var t = "";
            return a.isTotal && (t += "<em>共" + gTotalPage + '页，到<input class="pager-txt" type="text" maxLength=' + (gTotalPage + "").length + ' value="' + gCurPage + '">页</em>',
              t += '<a class="pg-btn" href="javascript:;">确定</a>'),
              t
          },
          makePager: function() {
            var t, e, i, n, r = o.getPagerCnt(), s = "", l = a.range - 1;
            if (gTotalPage > a.range && gCurPage <= gTotalPage) {
              for (t = parseInt(l / 2),
                     e = gCurPage + t,
                     i = gCurPage - (l - t),
                     e > gTotalPage ? (e = gTotalPage,
                       i = e - l) : 1 > i && (i = 1,
                       e = i + l),
                     n = i; n <= gCurPage - 1; n++)
                s += o.getPager(n);
              for (s += o.getCurPager(gCurPage),
                     n = gCurPage + 1; e >= n; n++)
                s += o.getPager(n);
              i > 1 && (s = o.getMore() + s),
              e < gTotalPage && (s += o.getMore()),
              gCurPage > 1 && (s = o.getPrev() + s),
              gCurPage < gTotalPage && (s += o.getNext()),
                s += o.getTotal(gCurPage)
            } else {
              for (n = 1; n <= gTotalPage; n++)
                s += n == gCurPage ? o.getCurPager(n) : o.getPager(n);
              gCurPage > 1 && (s = o.getPrev() + s),
              gCurPage < gTotalPage && (s += o.getNext())
            }
            r.html(s),
              $this.html(r)
          },
          go: function(t) {
            if (!gIsLoading)
              if (a.clickPageFun && a.clickPageFun(t),
                  gIsLoading = !0,
                  gCurPage = t,
                  a.ajax) {
                var n = e.extend(!0, {}, {
                  pageSize: a.pageSize,
                  curPage: gCurPage
                }, a.ajax.data);
                i.jsonp({
                  url: a.ajax.url,
                  data: n
                }, function(t) {
                  gTotalCount = t.totalCounts,
                    o.getTotalPage(),
                    a.paged(gCurPage, t),
                    o.makePager(),
                    gIsLoading = !1
                }, function(t) {
                  a.paged(gCurPage, t),
                    o.makePager(),
                    gIsLoading = !1
                })
              } else
                o.getTotalPage(),
                  a.paged(gCurPage),
                  o.makePager(),
                  gIsLoading = !1
          },
          initEvent: function() {
            $this.off(),
              $this.delegate(".pg-btn", "click", function() {
                var t = e(this).parent(".pager").find(".pager-txt");
                o.go(o.getValidPage(t.val()))
              }),
              $this.delegate(".pager-next", "click", function() {
                var t = gCurPage;
                t++,
                t <= gTotalPage && o.go(t)
              }),
              $this.delegate(".pager-prev", "click", function() {
                var t = gCurPage;
                t--,
                t > 0 && o.go(t)
              }),
              $this.delegate(".pager-txt", "keydown", function(t) {
                var i = e(this).parent("em").parent(".pager").find(".pager-txt");
                13 == t.keyCode && o.go(o.getValidPage(i.val()))
              }),
              $this.delegate(".pager-txt", "kedown", function(t) {
                var i = e(this)
                  , o = e.trim(i.val());
                o.length <= 0
              }),
              $this.delegate(".pager-item", "click", function(t) {
                var i = 1 * e(this).attr("data-page");
                o.go(i)
              })
          },
          getValidPage: function(t) {
            return /(^[1-9]?$)|(^[1-9][\d]*$)/.test(t) ? (t = parseInt(t),
            1 > t && (t = 1),
            t > gTotalPage && (t = gTotalPage)) : t = 1,
              $this.find(".pager-txt").val(t),
              t
          }
        },
        o.init()
    }
  }),
  define("module/item/1.0.0/common/jquery.demandLoading", ["require", "exports", "module", "jquery"], function(t, e, i) {
    "use strict";
    var o = t("jquery");
    o.fn.demandLoading = function(t) {
      var e = {
          range: 100,
          attr: "data-loading",
          container: o(window),
          callback: function() {}
        }
        , i = o.extend({}, e, t || {})
        , n = o(this)
        , a = function() {
          var t = o(i.container)
            , e = t.height()
            , a = t.scrollTop();
          n.each(function() {
            var t, n = o(this), r = n.offset().top - i.range, s = r + i.range + i.range + n.outerHeight();
            n.attr(i.attr) && (t = !(r > a + e || a > s),
            t && (i.callback && i.callback(),
              n.removeAttr(i.attr)))
          })
        }
        ;
      a(),
        i.container.bind("scroll", a),
        i.container.resize(a)
    }
  }),
  define("module/item/1.0.0/proinfo/comment", ["require", "exports", "module", "jquery", "bbg", "lib/template/3.0/template", "module/item/1.0.0/common/jquery.pager", "module/item/1.0.0/common/jquery.demandLoading", "lib/gallery/widget/lazyload/1.0.0/lazyload", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o, n, a, r, s = t("jquery");
    t("bbg"),
      o = t("lib/template/3.0/template"),
      t("module/item/1.0.0/common/jquery.pager"),
      t("module/item/1.0.0/common/jquery.demandLoading"),
      n = t("lib/gallery/widget/lazyload/1.0.0/lazyload"),
      a = t("module/item/1.0.0/common/common"),
      r = {
        dom: {
          $item: s("#jGoodsItem"),
          $tabTitle: s("#jNavTabTitle"),
          $pager: s("#jCommentPager"),
          $comment: s(".jComment"),
          $comments: s("#jProComments")
        },
        init: function() {
          var t = this;
          t.event()
        },
        _isFirstLoaded: !0,
        _bindPager: function(t) {
          var e = this
            , i = {
            shop_id: a.gShopId,
            goods_id: a.gGoodsId,
            get_stat: 0,
            type: "all"
          }
            , o = s.extend(i, t);
          e.dom.$pager.empty().pager({
            curPage: 1,
            pageSize: 10,
            range: 6,
            ajax: {
              url: "http://item.yunhou.com/index/ajaxGetCommentData",
              data: o
            },
            paged: function(t, i) {
              e.loading(i)
            },
            clickPageFun: function() {
              if (!e._isFirstLoaded) {
                var t = s(".jNavForComment");
                t.click()
              }
              e._isFirstLoaded = !1
            }
          })
        },
        event: function() {
          var t = this;
          s("#gd-comment").demandLoading({
            callback: function() {
              t._bindPager()
            }
          }),
            t.dom.$item.delegate(".jBtnCmdList", "click", function() {
              t.showDesignationBox()
            }),
            t.dom.$comment.delegate(".jReloadCmt", "click", function() {
              t._bindPager()
            }),
            t.dom.$comment.delegate(".jTabCmtTitle", "click", function() {
              var e = s(this)
                , i = {
                type: e.attr("data-type")
              };
              t._bindPager(i),
                t.tabs(e)
            })
        },
        tabs: function(t) {
          s(t).addClass("hover").siblings().removeClass("hover")
        },
        loading: function(t) {
          var e, i = this, l = "";
          l = !t || t._error || t.error && 0 != t.error ? '<div class="empty">评论加载失败，请<a href="javascript:;" class="jReloadCmt">重试</a></div>' : o.render("jTmplProComments", s.extend(t, {
            _gProductId: a.gProductId
          })),
            i.dom.$comment.html(l),
            e = i.dom.$comment.find(".jImg"),
            n.load(e, {
              loadingClass: ""
            }),
            r.emit("loaded")
        }
      },
      a.Emitter.applyTo(r),
      i.exports = {
        on: function(t, e) {
          r.on(t, e)
        },
        init: function() {
          r.init()
        }
      }
  }),
  define("module/item/1.0.0/proinfo/you-like", ["require", "exports", "module", "jquery", "bbg", "module/item/1.0.0/common/jquery.demandLoading", "lib/gallery/widget/lazyload/1.0.0/lazyload", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o, n, a, r = t("jquery");
    t("bbg"),
      t("module/item/1.0.0/common/jquery.demandLoading"),
      o = t("lib/gallery/widget/lazyload/1.0.0/lazyload"),
      n = t("module/item/1.0.0/common/common"),
      a = {
        init: function() {
          r("#jRecommend").demandLoading({
            callback: function() {
              a.hover(),
                a.jsonp()
            }
          })
        },
        hover: function() {
          r(".jFreshRec").hover(function() {
            r(".jFreshRec i").addClass("hover")
          }, function() {
            r(".jFreshRec i").removeClass("hover")
          })
        },
        jsonp: function() {
          var t = ""
            , e = r(".mod-recommend")
            , i = {
            productId: n.gProductId,
            num: 18
          };
          n.ajax("http://item.yunhou.com/Bi/getUmlikeList", i, function(e) {
            var i, o, n;
            return e.list && 0 != e.list.length ? (r("#jRecommend").show(),
              r(".mod-recommend").slideDown(),
              i = Math.ceil(e.list.length / 6),
            1 == i && r(".jFreshRec").hide(),
              o = function(i, o) {
                var n;
                for (i = i; o > i; i++)
                  e.list[i] && (t += '<li><a target="_blank" href="' + e.list[i].url + '" class="pro-img" data-bpm="8.1.1.' + (i + 1) + ".0." + e.list[i].productId + '"><img class="img-error img" title="' + e.list[i].name + '" src="' + e.list[i].img + '"  alt=""></a><a title="' + e.list[i].name + '" data-bpm="8.1.1.' + (i + 1) + ".0." + e.list[i].productId + '" target="_blank" href="' + e.list[i].url + '" class="pro-name">' + e.list[i].name + '</a><div class="pro-price"><span class="p-normal"><em>￥</em>' + e.list[i].price + "</span></div></li>");
                r("#jUmlikeList").html(t),
                  n = r("#jUmlikeList").find("img"),
                  t = ""
              }
              ,
              o(0, 6),
              n = r(".jFreshRec"),
              n.click(function() {
                var t = parseInt(n.attr("data-page"));
                return n.attr("data-page") == i ? (o(0, 6),
                  n.attr("data-page", "1"),
                  !1) : (o(6 * t, 6 * t + 6),
                  void n.attr("data-page", t + 1))
              }),
              void a.emit("loaded")) : void a.emit("loaded")
          }, function() {
            e.hide(),
              a.emit("loaded")
          })
        }
      },
      n.Emitter.applyTo(a),
      i.exports = {
        on: function(t, e) {
          a.on(t, e)
        },
        init: function() {
          a.init()
        }
      }
  }),
  define("module/item/1.0.0/common/slider", ["require", "exports", "module", "jquery"], function(t, e, i) {
    "use strict";
    function o(t) {
      this.opts = n.extend({}, a, t || {}),
        this.init()
    }
    var n = t("jquery")
      , a = {
      num: 3,
      content: ".jLazySlider",
      speed: 500,
      margin: 30,
      autoSpeed: 5e3,
      showWidth: "",
      auto: !1,
      focus: !0,
      showNum: 3,
      showFlag: !0,
      infinite: !0,
      imgEl: ".jSliderImg",
      afterCallback: function() {},
      beforeCallback: function() {}
    };
    return o.prototype = {
      init: function() {
        var t, e = this, i = n(e.opts.content).find(".jSliderShow .jSliderBox");
        return 0 == i.length ? !1 : i.length <= e.opts.showNum ? (e.imgLoad(e.opts.showNum, e.opts.showFlag),
          !1) : (t = (i.outerWidth() + Number(e.opts.margin)) * e.opts.num,
        e.opts.showWidth || (e.opts.showWidth = t),
          e.append(),
          e.prev(t),
          e.next(t),
        e.opts.focus && e.focusSel(),
          void (e.opts.auto && e.autoPlay()))
      },
      append: function() {
        var t, e, i, o = this, a = n(o.opts.content), r = a.find(".jSliderMod"), s = r.find(".jSliderBox"), l = s.length / o.opts.num;
        if (o.imgLoad(o.opts.showNum, o.opts.showFlag),
            n(o.opts.content).append('<a href="javascript:;" class="jSliderPrev icon iconfont prev">&#xe602;</a><a href="javascript:;" class="jSliderNext icon iconfont next">&#xe603;</a>'),
            o.opts.focus) {
          for (t = n('<div class="jSliderIndex slider-index"></div>'),
                 e = 0; l > e; e++)
            t.append('<a href="javascript:;"></a>');
          a.append(t),
            a.find(".jSliderIndex a").eq(0).addClass("hover")
        }
        o.opts.infinite ? (i = r.find("li:lt(" + o.opts.showNum + ")").clone(),
          r.append(i),
          r.find("li:gt(" + (s.length - o.opts.showNum) + ")").find(o.opts.imgEl).css("opacity", 1)) : a.find(".jSliderPrev").css("opacity", .1)
      },
      play: function(t, e, i) {
        var o, a = this, r = !0, s = null , l = a.opts, c = n(l.content), u = c.find(".jSliderMod"), d = u.find(".jSliderBox"), h = c.find(".jSliderIndex a"), m = u.position().left, p = d.outerWidth() + Number(a.opts.margin);
        s = a.opts.infinite ? l.showWidth * (d.length / l.num - 1) : l.showWidth * (d.length / l.num),
        a.opts.infinite || m != -s + p * l.showNum || 2 != e || (r = !1),
        m >= 0 && 1 == e && (a.opts.infinite || (r = !1),
        r && u.css({
          left: "-=" + s
        })),
        m <= -s + p * (l.showNum - 1) && 2 == e && r && u.css({
          left: 0
        }),
          m = u.position().left,
        l.beforeCallback && l.beforeCallback(),
        !u.is(":animated") && r && (o = 3 == e ? t : m + t,
          c.find(".jSliderMod").stop().animate({
            left: o
          }, l.speed, function() {
            a.imgLoad();
            var t = Math.floor(Math.abs(o / l.showWidth));
            t = t == h.length ? 0 : t,
              h.eq(t).addClass("hover").siblings().removeClass("hover"),
            l.afterCallback && l.afterCallback(a.opts),
            a.opts.infinite || c.find(i).css("opacity", .1)
          }))
      },
      prev: function(t) {
        var e = this;
        n(e.opts.content).find(".jSliderPrev").click(function() {
          n(e.opts.content).find(".jSliderNext").removeAttr("style"),
            e.play(t, 1, this)
        })
      },
      next: function(t) {
        var e = this;
        n(e.opts.content).find(".jSliderNext").click(function() {
          n(e.opts.content).find(".jSliderPrev").removeAttr("style"),
            e.play(-t, 2, this)
        })
      },
      focusSel: function() {
        var t = this
          , e = n(t.opts.content)
          , i = e.find(".jSliderMod")
          , o = i.find(".jSliderBox");
        i.position().left;
        e.find(".jSliderIndex a").mouseenter(function() {
          var e = n(this).index();
          t.opts.index = e,
            t.play(-((o.outerWidth() + t.opts.margin) * t.opts.num * e), 3)
        })
      },
      autoPlay: function() {
        var t = this
          , e = null
          , i = n(t.opts.content)
          , o = function() {
            var e = n(t.opts.content).find(".jSliderShow .jSliderBox")
              , i = (e.outerWidth() + Number(t.opts.margin)) * t.opts.num;
            n(t.opts.content).find(".jSliderPrev").removeAttr("style"),
              t.play(-i, 2)
          }
          ;
        i.mouseenter(function() {
          clearInterval(e)
        }),
          i.mouseleave(function() {
            e = setInterval(function() {
              o()
            }, t.opts.autoSpeed)
          }),
          e = setInterval(function() {
            o()
          }, t.opts.autoSpeed)
      },
      sliderImgLoad: function(t, e) {
        if (/^((https?|ftp|rmtp|mms):)?\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(t)) {
          var i = new Image;
          i.src = t,
            i.complete ? e.attr("src", t).removeClass("img-error").removeAttr("data-url").animate({
              opacity: 1
            }) : i.onload = function() {
              e.attr("src", t).removeClass("img-error").removeAttr("data-url").animate({
                opacity: 1
              })
            }
        }
      },
      imgLoad: function(t, e) {
        var i, o, a, r = this, s = n(r.opts.content), l = s.find(".jSliderMod"), c = l.find(".jSliderBox"), u = null , d = l.position().left, h = Math.floor(Math.abs(d / r.opts.showWidth)) || 0;
        for (u = e ? t : h * r.opts.num + r.opts.showNum,
               i = h * r.opts.num; u > i; i++)
          o = c.eq(i).find(r.opts.imgEl),
            a = o.attr("data-url"),
          a && r.sliderImgLoad(a, o)
      }
    },
      o
  }),
  define("module/item/1.0.0/proinfo/history", ["require", "exports", "module", "jquery", "lib/gallery/widget/lazyload/1.0.0/lazyload", "lib/gallery/utils/1.0.0/image", "pub/module/ajax/1.0.0/ajax", "module/item/1.0.0/common/slider", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o = t("jquery")
      , n = t("lib/gallery/widget/lazyload/1.0.0/lazyload")
      , a = t("lib/gallery/utils/1.0.0/image")
      , r = t("pub/module/ajax/1.0.0/ajax")
      , s = t("module/item/1.0.0/common/slider")
      , l = t("module/item/1.0.0/common/common")
      , c = {
      dom: {
        $historyList: o("#jHistoryList")
      },
      init: function() {
        c.initEvent()
      },
      initEvent: function() {
        var t = this;
        c.loading(),
          t.dom.$historyList.delegate(".jReloadHistory", "click", function() {
            c.loading()
          })
      },
      loading: function() {
        var t = this;
        r.jsonp({
          url: "http://item.yunhou.com/index/ajaxGetHistoryList",
          data: {
            goods_id: l.gGoodsId,
            product_id: l.gProductId
          }
        }, function(t) {
          var e = t && t.list && 0 != t.list.length;
          e && c.bind(t),
            o("#jHistoryBox")[e ? "show" : "hide"](),
            c.emit("loaded")
        }, function(e) {
          t.dom.$historyList.html('<div class="empty">加载失败，请<a class="jReloadHistory" href="javascript:;">重试<a/></div>'),
            c.emit("loaded")
        })
      },
      bind: function(t) {
        var e, i, o, r = "", l = this;
        if (t && t.list && t.list.length > 0) {
          for (r = '<ul class="pro-list clearfix jSliderMod">',
                 e = 0; e < t.list.length; e++)
            i = t.list[e],
              r += '<li class="jSliderBox">',
              r += '    <a target="_blank" title="' + i.name + '" href="' + i.url + '" class="pro-img" data-bpm="6.1.1.' + (e + 1) + ".0." + i.productId + '">',
              r += '        <img class="img-error jImg" alt="' + i.name + '" src="//static5.bubugao.com/public/img/blank.gif" data-url="' + a.getImageURL(i.img, "m1") + '">',
              r += '        <div class="pro-price">',
              r += '            <span class="p-normal"><em>￥</em>' + i.price + "</span>",
              r += "        </div>",
              r += "    </a>",
              r += "</li>";
          r += "</ul>"
        } else
          r = '<div class="empty1">暂无浏览记录</div>';
        l.dom.$historyList.html(r),
          o = l.dom.$historyList.find(".jImg"),
          new n(o,{
            effect: "fadeIn",
            dataAttribute: "url"
          }),
          new s({
            num: 10,
            showNum: 10,
            content: ".jLazySlider",
            margin: 20,
            focus: !1,
            infinite: !1,
            auto: !1,
            imgEl: ".jImg"
          })
      }
    };
    l.Emitter.applyTo(c),
      i.exports = {
        on: function(t, e) {
          c.on(t, e)
        },
        init: function() {
          c.init()
        }
      }
  }),
  define("module/item/1.0.0/proinfo/proinfo", ["require", "exports", "module", "module/item/1.0.0/proinfo/anchor", "module/item/1.0.0/proinfo/imgload", "module/item/1.0.0/proinfo/comment", "module/item/1.0.0/proinfo/you-like", "module/item/1.0.0/proinfo/history", "module/item/1.0.0/common/common"], function(t, e, i) {
    "use strict";
    var o = t("module/item/1.0.0/proinfo/anchor")
      , n = t("module/item/1.0.0/proinfo/imgload")
      , a = t("module/item/1.0.0/proinfo/comment")
      , r = t("module/item/1.0.0/proinfo/you-like")
      , s = t("module/item/1.0.0/proinfo/history")
      , l = t("module/item/1.0.0/common/common")
      , c = {
      showPriceBox: function() {
        var t, e, i = $.trim($(".jFirstPrice").text()), o = $.trim($(".jSecondPrice").text());
        o = 0 == o.length ? "" : "<em>¥</em>" + o,
          t = $("#jBtnOnly").html(),
          e = ["<li>", '<span class="pri-now">', "<em>¥</em>" + i + "</span>", '<span class="pri-del">', o + "</span>", "</li>", "<li>" + t + "</li>"],
          $("#jPriceBox").html(e.join(""))
      },
      initMod: function() {
        o.init(),
          n.init(),
          s.init()
      },
      init: function() {
        r.init()
      }
    };
    r.on("loaded", function() {
      c.initMod()
    }),
      n.on("loaded", function() {
        var t, e;
        a.init(),
          t = l.getHash(),
          t ? (e = t.replace("gd-", ""),
            $("[data-type=" + e + "]").click()) : o.setSelectedTab()
      }),
      a.on("loaded", function() {}),
      o.on("afterTabClick", function() {
        o.setSelectedTab()
      }),
      i.exports = {
        init: function() {
          c.init()
        },
        showPriceBox: function() {
          c.showPriceBox()
        }
      }
  }),
  define("conf/item/1.0.0/item", ["require", "exports", "module", "jquery", "module/item/1.0.0/baseinfo/promotion/info", "module/item/1.0.0/baseinfo/promotion/common", "module/item/1.0.0/baseinfo/preview-and-share", "module/item/1.0.0/baseinfo/collect", "module/item/1.0.0/proinfo/proinfo"], function(t, e, i) {
    "use strict";
    var o = (t("jquery"),
      t("module/item/1.0.0/baseinfo/promotion/info"))
      , n = t("module/item/1.0.0/baseinfo/promotion/common")
      , a = t("module/item/1.0.0/baseinfo/preview-and-share")
      , r = t("module/item/1.0.0/baseinfo/collect")
      , s = t("module/item/1.0.0/proinfo/proinfo");
    n.on("reloadInit", function() {
      o.init()
    }),
      r.on("changeCollectBtnStatus", function() {
        o.init()
      }).on("addFavorite", function() {}),
      o.on("loaded", function() {
        a.share(),
          s.init(),
          s.showPriceBox()
      }),
      r.init()
  });
