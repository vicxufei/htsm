/**
 * Created by yefeng on 16/4/20.
 */
define("module/cart/common", ["require", "exports", "module", "jquery", "lib/ui/box/1.0.1/box", "pub/module/ajax/1.0.0/ajax", "module/common/url-prefix"], function(t, e, n) {
  "use strict";
  var r = t("jquery")
    , i = t("lib/ui/box/1.0.1/box")
    , a = t("pub/module/ajax/1.0.0/ajax")
    , o = t("module/common/url-prefix")
    , l = {
    ajax: function(t, e, n, l, s) {
      var c = {
        url: t,
        dataType: "jsonp",
        jsonp: "callback",
        data: e,
        sucCallback: function(t) {
          t && t._error && t._error.msg ? i.error(t._error.msg) : (r.extend(t || {}, {
            _urlPrefix: o
          }),
          n && n(t))
        },
        errCallback: function(t) {
          var e = ""
            , n = "";
          (t._error || t.error && 0 != t.error) && (e = t._error ? t._error.code : t.error,
            n = t._error ? t._error.msg : t.msg),
          0 != n.length && i.warn(n, s),
          l && l(t)
        }
      };
      a.jsonp(c, c.sucCallback, c.errCallback, s)
    },
    delay: function() {
      var t = 0;
      return function(e, n) {
        clearTimeout(t),
          t = setTimeout(e, n)
      }
    },
    isEmptyObject: function(t) {
      for (var e in t)
        return !1;
      return !0
    }
  };
  l.o = r("#jCart"),
    n.exports = l
}),
  define("module/cart/list/get-product", ["require", "exports", "module", "jquery"], function(t, e, n) {
    "use strict";
    var r = t("jquery");
    r.fn.getProducts = function(t) {
      var e = {
        isChecked: !0
      }
        , n = r.extend({}, e, t || {})
        , i = {}
        , a = ""
        , o = ""
        , l = 0
        , s = r(this);
      return s.each(function() {
        var t = r(this).parents(".jTable")
          , e = t.find(".jChkItem")
          , i = t.find(".jQtyTxt");
        n.isChecked && !e.prop("checked") || (0 == l ? (a = t.attr("data-id"),
          o = i.val()) : (a += "," + t.attr("data-id"),
          o += "," + i.val())),
          l++
      }),
        i = {
          productId: a,
          quantity: o
        }
    }
  }),
  define("module/cart/list/chk", ["require", "exports", "module", "jquery", "module/cart/list/get-product", "lib/gallery/channel/1.0.0/channel", "module/cart/common", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = (t("module/cart/list/get-product"),
      r("#jCart"))
      , a = t("lib/gallery/channel/1.0.0/channel")
      , o = a.get("cartPage")
      , l = t("module/cart/common")
      , s = t("module/common/url-prefix")
      , c = {
      chkCur: function(t) {
        var e = this
          , n = t.parents(".jTable")
          , i = n.find(".jChkItem");
        r(this).prop("disabled") || (i.prop("checked", !0),
          e.setChk())
      },
      warehouseChecked: function(t) {
        var e = t.getProducts();
        l.ajax(s["api.cart"] + "/cart/checked", {
          productId: e.productId
        }, function(t) {
          o.fire("refreshList", t)
        })
      },
      setChk: function() {
        this.isChkCatAll(),
          this.isChkWarehouse(),
          this.isChkAll()
      },
      isChkWarehouse: function() {
        var t = i.find(".jWarehouseChk");
        t.each(function() {
          var t = r(this).closest(".jPkg")
            , e = t.find(".jChkItem:not(:disabled)")
            , n = e.filter(":checked");
          r(this).prop("checked", 0 != e.length && e.length == n.length)
        })
      },
      isChkCatAll: function() {
        var t = this
          , e = i.find(".jChkCat");
        e.each(function() {
          var t = r(this)
            , e = t.parent("h1").parent(".list-border").find(".jChkItem")
            , n = e.length
            , i = 0
            , a = 0;
          e.each(function() {
            r(this).prop("checked") && i++,
            r(this).prop("disabled") && a++
          }),
            n == i + a && a != n ? t.prop("checked", !0) : t.prop("checked", !1)
        }),
          t.isChkAll()
      },
      isChkAll: function() {
        var t = i.find(".jChkAll")
          , e = i.find(".jChkItem:not(:disabled)")
          , n = e.length
          , r = e.filter(":checked").length;
        t.prop("checked", 0 != n && r == n)
      },
      check: function(t) {
        var e = t.getProducts();
        l.ajax(s["api.cart"] + "/cart/checked", {
          productId: e.productId
        }, function(t) {
          o.fire("refreshList", t)
        })
      },
      _events: function() {
        var t = this;
        i.on("click", ".jChkAll", function() {
          var e = r(this)
            , n = i.find(".jChkItem")
            , a = e.prop("checked");
          e.hasClass("btn-disabled") || (n.each(function() {
            var t = r(this);
            t.prop("disabled") || t.prop("checked", a)
          }),
            t.setChk(),
            t.check(i.find(".jChkItem")))
        })
      }
    };
    c._events(),
      n.exports = c
  }),
  function(t, e, n) {
    "function" == typeof define && define.amd ? define("lib/core/1.0.0/io/messenger", n) : t[e] = n()
  }(this, "Messenger", function() {
    "use strict";
    function t(t, e) {
      var n = "";
      if (arguments.length < 2 ? n = "target and channelId are both requied" : "object" != typeof e ? n = "target must be window object" : "string" != typeof t && (n = "target channelId must be string type"),
          n)
        throw "TargetError: " + n;
      this._channelId = t,
        this.target = e
    }
    function e(t) {
      arguments.length > 1 && (t = arguments[1]);
      var t = t || "$";
      if (n[t])
        throw 'DuplicateError: a channel with id "' + t + '" is already exists.';
      n[t] = 1,
        this._attached = !1,
        this._targets = [],
        this.handleMessage = m(this.handleMessage, this),
        this._channelId = t,
        this._listeners = {}
    }
    var n, r = window, i = r.document, a = r.navigator, o = Object.prototype.hasOwnProperty, l = "postMessage" in r, s = "addEventListener" in i, c = r.JSON || 0, u = "__mc__", d = "<__JSON__>", h = function(t) {
        var e;
        if (!t || "object" != typeof t || t.nodeType || t.window === t)
          return !1;
        try {
          if (t.constructor && !o.call(t, "constructor") && !o.call(t.constructor.prototype, "isPrototypeOf"))
            return !1
        } catch (n) {
          return !1
        }
        for (e in t)
          ;
        return void 0 === e || o.call(t, e)
      }
      , f = c.stringify || function b(t) {
          var e, n, r, i, a = typeof t;
          if ("object" !== a || null  === t)
            return "string" === a && (t = '"' + t + '"'),
              String(t);
          r = [],
            i = t && t.constructor == Array;
          for (e in t)
            n = t[e],
              a = typeof n,
            t.hasOwnProperty(e) && ("string" === a ? n = '"' + n + '"' : "object" === a && null  !== n && (n = b(n)),
              r.push((i ? "" : '"' + e + '":') + String(n)));
          return (i ? "[" : "{") + String(r) + (i ? "]" : "}")
        }
      , p = c.parse || function(t) {
          return (0,
            r.eval)("(" + t + ")")
        }
      , m = function(t, e) {
        return t.bind ? t.bind(e) : function() {
          t.apply(e, arguments)
        }
      }
      , g = function(t, e) {
        for (var n in e)
          e.hasOwnProperty(n) && (t[n] = e[n]);
        return t
      }
      ;
    return t.prototype.send = l ? function(t) {
      this.target.postMessage(this._channelId + t, "*")
    }
      : function(t) {
      var e = this._channelId
        , n = a[e];
      if ("function" != typeof n)
        throw 'Target (channel="' + e + '") callback function is not defined';
      try {
        n(e + t, r)
      } catch (i) {}
    }
      ,
      n = {},
      g(e.prototype, {
        _mId: 0,
        _initMessenger: function() {
          var t, e = this;
          e._attached || (e._attached = !0,
            t = e.handleMessage,
            l ? s ? r.addEventListener("message", t, !1) : r.attachEvent("onmessage", t) : a[e._channelId] = t)
        },
        addTarget: function(e) {
          for (var n = this._targets, r = n.length; r--; )
            if (n[r][0] === e)
              return this;
          return n.push([e, new t(this._channelId,e)]),
            this
        },
        removeTarget: function(t) {
          for (var e = this._targets, n = e.length; n--; )
            e[n][0] === t && e.splice(n, 1);
          return this
        },
        handleMessage: function(t) {
          var e, n, r, i, a, o, l = this;
          if (t && (e = t,
              o = this._channelId,
            "object" == typeof t && (e = t.data),
            0 === e.indexOf(o))) {
            if (e = e.substring(o.length),
              0 === e.indexOf(d))
              try {
                r = p(e.substring(d.length))
              } catch (t) {
                setTimeout(function() {
                  console.error(t, e)
                }, 1)
              }
            else
              r = {
                type: "*",
                data: e
              };
            (n = r && r.type) && (i = t.source,
              a = {
                target: t.target,
                source: i,
                send: function(t) {
                  l.addTarget(i),
                    l.send(u + o + "_" + r.mId, t),
                    l.removeTarget(i)
                }
              },
              l.emit(n, r.data, a))
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
        sendMessage: function(t, e, n) {
          var r, i, a, o, l;
          if ("function" == typeof e && (n = e,
              e = void 0),
            "object" == typeof e && !h(e))
            throw "The CORSS message data not a valid plain object.";
          for (r = ++this._mId,
                 i = this._channelId,
                 a = this._targets,
                 o = {
                   type: t,
                   data: e,
                   mId: r
                 },
               "function" == typeof n && this.on(u + i + "_" + r, function(e) {
                 n({
                   type: t,
                   data: e,
                   mId: r
                 })
               }),
                 e = d + f(o),
                 l = a.length; l--; )
            a[l][1].send(e);
          return this
        },
        send: function(t, e, n) {
          return void 0 === e && (e = t,
            t = "*"),
            this.sendMessage(t, e, n)
        },
        on: function(t, e) {
          return this._initMessenger(),
          this._listeners[t] || (this._listeners[t] = []),
          "function" == typeof e && this._listeners[t].push(e),
            this
        },
        emit: function(t) {
          var e, n, r, i = this._listeners[t];
          if (i)
            for (e = 0,
                   n = i.length,
                   r = Array.prototype.slice.call(arguments, 1); n > e; e++)
              i[e].apply(this, r);
          return this
        },
        destroy: function() {
          if (l) {
            var t = this.handleMessage;
            s ? r.removeEventListener("message", t) : r.detachEvent("onmessage", t)
          } else
            delete a[this._channelId];
          this.clear(),
            this._targets.length = 0,
            delete n[this._channelId]
        }
      }),
      e
  }),
  define("lib/gallery/ssologin/1.0.0/ssologin", ["require", "exports", "module", "lib/ui/box/1.0.1/box", "lib/core/1.0.0/io/messenger", "./channel"], function(t, e, n) {
    "use strict";
    var r, i = t("lib/ui/box/1.0.1/box"), a = t("lib/core/1.0.0/io/messenger"), o = function(t, e) {
        for (var n in e)
          e.hasOwnProperty(n) && (t[n] = e[n]);
        return t
      }
      , l = t("./channel"), s = "yunhou.com", c = "https://ssl.yunhou.com/passport/loginMini", u = function(t, e, n, u) {
        var d, h;
        "function" == typeof t && (u = n,
          n = e,
          e = t,
          t = null ),
        r && r.destroy(),
          t = o({
            url: c,
            width: 380,
            height: 440,
            fixed: !0,
            scrolling: "no",
            close: !1
          }, t),
          d = t.domain || s;
        try {
          document.domain = d
        } catch (f) {
          setTimeout(function() {
            console.warn(f)
          }, 1)
        }
        return r = i.loadUrl(t.url, t),
        u && r.on("hide", function() {
          u()
        }),
          h = new a("channel/passport"),
          h.on("resizeBox", function(t) {
            r.height(t.height)
          }),
          h.on("loginCallback", function(t) {
            l.fire("login", t),
            e && e(t),
              r.hide()
          }),
          h.on("loginErrorCallback", function(t) {
            l.fire("error", t),
            n && n(t)
          }),
          r.on("destroy", function(t) {
            r = null ,
              h.destroy()
          }),
          h.on("loginHide", function(t) {
            r.hide()
          }),
          r
      }
      ;
    n.exports = {
      showDialog: u
    }
  }),
  define("plug/jquery.goodsCol", ["require", "exports", "module", "jquery", "lib/core/1.0.0/io/cookie", "lib/gallery/ssologin/1.0.0/ssologin", "pub/module/ajax/1.0.0/ajax", "lib/ui/box/1.0.1/box", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = t("lib/core/1.0.0/io/cookie")
      , a = t("lib/gallery/ssologin/1.0.0/ssologin")
      , o = t("pub/module/ajax/1.0.0/ajax")
      , l = t("lib/ui/box/1.0.1/box")
      , s = t("module/common/url-prefix");
    r.fn.goodsCol = function(t) {
      var e = {
        event: null ,
        btn: null ,
        colText: "已收藏",
        colClass: "btn-disabled",
        callback: function() {}
      }
        , n = r.extend({}, e, t || {});
      r(this).each(function() {
        function t() {
          var t, r = i("_nick");
          r ? (t = c.attr("data-goods-id"),
            o.jsonp({
              url: s["api.mall"] + "/product/addfavorite",
              data: {
                productId: c.attr("data-product-id"),
                goodsId: c.attr("data-goods-id")
              }
            }, function(t) {
              var e = n.colText;
              "number" == typeof t.favoriteCount && t.favoriteCount >= 0 && (e += "(" + t.favoriteCount + ")"),
                setTimeout(function() {
                  c.addClass(n.colClass).html(e)
                }, 0)
            }, function(t) {
              "-100" == t._error.code ? e() : l.warn(t._error.msg, c)
            }, c)) : e()
        }
        function e() {
          a.showDialog(function(e) {
            t()
          }, function(t) {})
        }
        var c = r(this);
        c.hasClass("btn-disabled") || (n.event ? c.click(n.event, function() {
          t()
        }) : t())
      })
    }
  }),
  !function(t) {
    "use strict";
    var e, n, r, i, a = function(t, e) {
        return a["string" == typeof e ? "compile" : "render"].apply(a, arguments)
      }
      ;
    a.version = "2.0.4",
      a.openTag = "<%",
      a.closeTag = "%>",
      a.isEscape = !0,
      a.isCompress = !1,
      a.parser = null ,
      a.render = function(t, e) {
        var n = a.get(t) || r({
            id: t,
            name: "Render Error",
            message: "No Template"
          });
        return n(e)
      }
      ,
      a.compile = function(t, n) {
        function o(e) {
          try {
            return new l(e,t) + ""
          } catch (i) {
            return c ? r(i)() : a.compile(t, n, !0)(e)
          }
        }
        var l, s = arguments, c = s[2], u = "anonymous";
        "string" != typeof n && (c = s[1],
          n = s[0],
          t = u);
        try {
          l = i(t, n, c)
        } catch (d) {
          return d.id = t || n,
            d.name = "Syntax Error",
            r(d)
        }
        return o.prototype = l.prototype,
          o.toString = function() {
            return l.toString()
          }
          ,
        t !== u && (e[t] = o),
          o
      }
      ,
      e = a.cache = {},
      n = a.helpers = function() {
        var t = function(e, n) {
            return "string" != typeof e && (n = typeof e,
              "number" === n ? e += "" : e = "function" === n ? t(e.call(e)) : ""),
              e
          }
          , e = {
            "<": "&#60;",
            ">": "&#62;",
            '"': "&#34;",
            "'": "&#39;",
            "&": "&#38;"
          }
          , n = function(n) {
            return t(n).replace(/&(?![\w#]+;)|[<>"']/g, function(t) {
              return e[t]
            })
          }
          , r = Array.isArray || function(t) {
              return "[object Array]" === {}.toString.call(t)
            }
          , i = function(t, e) {
            if (r(t))
              for (var n = 0, i = t.length; i > n; n++)
                e.call(t, t[n], n, t);
            else
              for (n in t)
                e.call(t, t[n], n)
          }
          ;
        return {
          $include: a.render,
          $string: t,
          $escape: n,
          $each: i
        }
      }(),
      a.helper = function(t, e) {
        n[t] = e
      }
      ,
      a.onerror = function(e) {
        var n, r = "Template Error\n\n";
        for (n in e)
          r += "<" + n + ">\n" + e[n] + "\n\n";
        t.console && console.error(r)
      }
      ,
      a.get = function(n) {
        var r, i, o;
        return e.hasOwnProperty(n) ? r = e[n] : "document" in t && (i = document.getElementById(n),
        i && (o = i.value || i.innerHTML,
          r = a.compile(n, o.replace(/^\s*|\s*$/g, "")))),
          r
      }
      ,
      r = function(t) {
        return a.onerror(t),
          function() {
            return "{Template Error}"
          }
      }
      ,
      i = function() {
        var t = n.$each
          , e = "break,case,catch,continue,debugger,default,delete,do,else,false,finally,for,function,if,in,instanceof,new,null,return,switch,this,throw,true,try,typeof,var,void,while,with,abstract,boolean,byte,char,class,const,double,enum,export,extends,final,float,goto,implements,import,int,interface,long,native,package,private,protected,public,short,static,super,synchronized,throws,transient,volatile,arguments,let,yield,undefined"
          , r = /\/\*[\w\W]*?\*\/|\/\/[^\n]*\n|\/\/[^\n]*$|"(?:[^"\\]|\\[\w\W])*"|'(?:[^'\\]|\\[\w\W])*'|[\s\t\n]*\.[\s\t\n]*[$\w\.]+/g
          , i = /[^\w$]+/g
          , o = new RegExp(["\\b" + e.replace(/,/g, "\\b|\\b") + "\\b"].join("|"),"g")
          , l = /^\d[^,]*|,\d[^,]*/g
          , s = /^,+|,+$/g
          , c = function(t) {
            return t.replace(r, "").replace(i, ",").replace(o, "").replace(l, "").replace(s, "").split(/^$|,+/)
          }
          ;
        return function(e, r, i) {
          function o(t) {
            return v += t.split(/\n/).length - 1,
            a.isCompress && (t = t.replace(/[\n\r\t\s]+/g, " ").replace(/<!--.*?-->/g, "")),
            t && (t = k[1] + d(t) + k[2] + "\n"),
              t
          }
          function l(t) {
            var e, r, o = v;
            return m ? t = m(t) : i && (t = t.replace(/\n/g, function() {
              return v++,
              "$line=" + v + ";"
            })),
            0 === t.indexOf("=") && (e = !/^=[=#]/.test(t),
              t = t.replace(/^=[=#]?|[\s;]*$/g, ""),
              e && a.isEscape ? (r = t.replace(/\s*\([^\)]+\)/, ""),
              n.hasOwnProperty(r) || /^(include|print)$/.test(r) || (t = "$escape(" + t + ")")) : t = "$string(" + t + ")",
              t = k[1] + t + k[2]),
            i && (t = "$line=" + o + ";" + t),
              s(t),
            t + "\n"
          }
          function s(e) {
            e = c(e),
              t(e, function(t) {
                t && !y.hasOwnProperty(t) && (u(t),
                  y[t] = !0)
              })
          }
          function u(t) {
            var e;
            "print" === t ? e = w : "include" === t ? (j.$include = n.$include,
              e = I) : (e = "$data." + t,
            n.hasOwnProperty(t) && (j[t] = n[t],
              e = 0 === t.indexOf("$") ? "$helpers." + t : e + "===undefined?$helpers." + t + ":" + e)),
              x += t + "=" + e + ","
          }
          function d(t) {
            return "'" + t.replace(/('|\\)/g, "\\$1").replace(/\r/g, "\\r").replace(/\n/g, "\\n") + "'"
          }
          var h, f = a.openTag, p = a.closeTag, m = a.parser, g = r, b = "", v = 1, y = {
            $data: 1,
            $id: 1,
            $helpers: 1,
            $out: 1,
            $line: 1
          }, j = {}, x = "var $helpers=this," + (i ? "$line=0," : ""), _ = "".trim, k = _ ? ["$out='';", "$out+=", ";", "$out"] : ["$out=[];", "$out.push(", ");", "$out.join('')"], C = _ ? "$out+=$text;return $text;" : "$out.push($text);", w = "function($text){" + C + "}", I = "function(id,data){data=data||$data;var $text=$helpers.$include(id,data,$id);" + C + "}";
          t(g.split(f), function(t) {
            t = t.split(p);
            var e = t[0]
              , n = t[1];
            1 === t.length ? b += o(e) : (b += l(e),
            n && (b += o(n)))
          }),
            g = b,
          i && (g = "try{" + g + "}catch(e){throw {id:$id,name:'Render Error',message:e.message,line:$line,source:" + d(r) + ".split(/\\n/)[$line-1].replace(/^[\\s\\t]+/,'')};}"),
            g = x + k[0] + g + "return new String(" + k[3] + ");";
          try {
            return h = new Function("$data","$id",g),
              h.prototype = j,
              h
          } catch ($) {
            throw $.temp = "function anonymous($data,$id) {" + g + "}",
              $
          }
        }
      }(),
      "function" == typeof define ? define("template", [], function() {
        return a
      }) : "undefined" != typeof exports && (module.exports = a),
      t.template = a
  }(this),
  define("module/cart/list/ctn/module", ["require", "exports", "module", "jquery", "template"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = t("template")
      , a = {
      _getHdData: function(t) {
        var e, n, r, i, a, o;
        return t.products && 0 != t.products.length ? (e = 3 == t.shopType,
          n = t.shopTag && 0 != t.shopTag.length ? t.shopTag : !1,
          r = "http://wpa.qq.com/msgrd?v=3&amp;uin=" + t.qq + "&amp;site=qq&amp;menu=yes",
          i = "",
          a = t.tipStatus,
          o = "",
        (e || 4 == t.shopType) && (r = "http://www.sobot.com/chat/pc/index.html?sysNum=dc4ff8354775446d8fcfe7669014bbef"),
        n && (i = '<em class="smart-monkey">' + n + "</em>"),
        !a || 1 != a && 2 != a || (o = '<div class="market-tips"><b class="tit-b">温馨提示：</b>' + t.switchTips + "</div>"),
        {
          _globalTxt: i,
          _kefuUrl: r,
          _tipsStr: o
        }) : {}
      },
      _getProductData: function(t) {
        if (!t || 0 == t.length)
          return [];
        var e = this;
        return r.each(t, function(t, n) {
          var r, i, a, o = n, l = "", s = "", c = "", u = "";
          o.canSelected && o.selected && (l = 'checked = "checked"',
            u = "table-checked"),
          o.canSelected || (s = 'disabled = "disabled"',
            c = " list-disabled"),
            r = "",
          o.qtyTips.length > 0 && (r = '<div class="tight-inventory">' + o.qtyTips + "</div>"),
            i = '<a class="jCol" href="javascript:;">收藏</a>',
          o.favorite && (i = '<a class="jCol btn-disabled" href="javascript:;">已收藏</a>'),
            a = o.price,
          o.mktprice && 0 != o.mktprice.length && (a = o.mktprice),
            n._specHtml = e._getSpecData(n.specList),
            n._taxHtml = e._getTax(n),
            n._promotionsHtml = e._getPromotionData(n.promotions, ""),
            n._checked = l,
            n._tableStyle = u,
            n._disabled = s,
            n._styleDisabled = c,
            n._qtyTips = r,
            n._strFavorite = i,
            n._originPrice = a
        }),
          t
      },
      _getTax: function(t) {
        var e, n, r, i, a, o, l, s, c, u, d, h;
        return t && t.taxation && 0 != t.taxation.length ? (e = [],
          n = t.productRule,
          r = t.dutiablePrice,
          i = t.freightAmount,
          a = t.productTaxationTips,
          o = t.taxation,
          l = n && 0 != n.length ? "<p>" + n + "</p>" : "",
          s = void 0 != r && 0 != r.length && void 0 != i && 0 != i.length ? "<p>当前商品优惠后单价为:" + r + ",运费:" + i + "；</p>" : "",
          c = a && 0 != a.length ? "<p>" + a + "</p>" : "",
          u = l + s + c,
          d = 0 == u.length ? "" : '<i class="icon iconfont ico-tariff jTariffIco">&#xe601;</i>',
          h = 0 == t.beTaxation ? " text-del" : "",
          e.push('<div class=" tariff-p">', '<div class="jMousePop tariff-inner">', "<span>税费:</span>", '<span class="ico-txt ' + h + '">￥' + o + "</span>", d, '<textarea style="display:none" class="jCtnText"><div style="font-family:\'宋体\';font-size:12px;">' + u + "</div></textarea>", "</div>", "</div>"),
          e.join("")) : ""
      },
      _getOtherListData: function(t) {
        if (!t || 0 == t.length)
          return [];
        var e = this;
        return r.each(t, function(t, n) {
          n._specHtml = e._getSpecData(n.specList),
            n._taxHtml = e._getTax(n),
            n._promotionsHtml = e._getPromotionData(n.promotions, n.productId)
        }),
          t
      },
      _getSpecData: function(t) {
        return t && 0 != t.length ? '<p class="specification">' + r(t).map(function() {
          return this.name + ":" + this.value
        }).get().join(",") + "</p>" : ""
      },
      _getPromotionData: function(t, e) {
        return t && 0 != t.length ? (r.each(t, function(t, e) {
          var n = e.url
            , r = n && 0 != n.length ? "a" : "span";
          e._tagStr = r
        }),
          i("cart/list/warehouse/items/info/promotions", {
            promotions: t,
            productId: e
          })) : ""
      }
    };
    n.exports = a
  }),
  define("module/cart/list/ctn/qty-box", ["require", "exports", "module", "jquery", "lib/ui/box/1.0.1/box", "module/cart/list/chk", "module/cart/list/get-product", "module/cart/common", "lib/gallery/channel/1.0.0/channel", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = r("#jCart")
      , a = t("lib/ui/box/1.0.1/box")
      , o = t("module/cart/list/chk")
      , l = (t("module/cart/list/get-product"),
      t("module/cart/common"))
      , s = t("lib/gallery/channel/1.0.0/channel")
      , c = s.get("cartPage")
      , u = t("module/common/url-prefix")
      , d = {
      init: function() {},
      inputUpdate: function(t) {
        var e = 1 * t.attr("data-max")
          , n = t.val();
        0 == r.trim(n).length && (n = 1),
          /(^[1-9]?$)|(^[1-9][\d]*$)/.test(n) ? (n = parseInt(n),
          1 > n && (n = 1),
          n > e && (n = e)) : n = 1,
          t.val(n)
      },
      update: function(t) {
        o.chkCur(t);
        var e = t.getProducts();
        l.ajax(u["api.cart"] + "/cart/update", {
          productId: e.productId,
          quantity: e.quantity
        }, function(t) {
          c.fire("refreshList", t)
        })
      },
      _events: function() {
        var t = this;
        i.on("click", ".jQtyMin", function() {
          var e = r(this)
            , n = e.siblings(".jQtyTxt")
            , i = 1 * n.val()
            , o = 1 == r(this).closest(".jQty").attr("data-q-flag");
          e.hasClass("btn-disabled") || o || (i--,
            i > 0 ? (n.val(i),
              t.update(e)) : a.warn("此商品最小购买数量为1", n[0]))
        }),
          i.on("change", ".jQtyTxt", function() {
            var e = r(this)
              , n = Number(e.attr("data-max"))
              , i = Number(e.val())
              , o = 1 == r(this).closest(".jQty").attr("data-q-flag");
            e.hasClass("btn-disabled") || o || (isNaN(i) ? e.val("1") : i > n ? (e.val(n),
              a.warn("库存有限，此商品最多只能购买" + n + "件", e[0])) : 0 >= i && (e.val("1"),
              a.warn("此商品最小购买数量为1", e[0])),
              t.update(e))
          }),
          i.on("click", ".jQtyAdd", function() {
            var e = r(this)
              , n = e.siblings(".jQtyTxt")
              , i = 1 * n.attr("data-max")
              , o = 1 * n.val()
              , l = 1 == r(this).closest(".jQty").attr("data-q-flag");
            e.hasClass("btn-disabled") || l || (o++,
              i >= o ? (n.val(o),
                t.update(e)) : a.warn("库存有限，此商品最多只能购买" + i + "件", n[0]))
          })
      }
    };
    d._events(),
      n.exports = d
  }),
  define("module/cart/list/ctn/preferential", ["require", "exports", "module", "jquery"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = r("#jCart")
      , a = {
      getTabHtml: function(t, e, n) {
        return t && 0 != t.length ? template("cart/list/preferential/tab", {
          _title: e ? "已享受" : "未享受",
          preferential: t,
          _totalDiscount: e ? n : ""
        }) : ""
      },
      getCtnHtml: function(t, e) {
        return t && 0 != t.length ? (r.each(t, function(t, n) {
          var r, i, a, o, l = "";
          e && (r = n.toolCode,
            r = r && "ump-order-coupon" == r || !1,
            l = r ? "send-coupons" : "straight-cut"),
            i = n.url,
            a = !i || 0 == i.length,
            o = a ? "span" : "a",
            n._classStr = l,
            n._tagStr = o
        }),
          template("cart/list/preferential/ctn", {
            preferential: t
          })) : ""
      },
      _events: function() {
        i.on("click", ".jPrefItem", function() {
          var t = r(this).closest(".jPreferentWrap")
            , e = t.find(".jPrefItem")
            , n = e.index(r(this))
            , i = t.find(".jCtnUl")
            , a = i.eq(n).is(":visible");
          e.removeClass("item-hover"),
            r(this)[a ? "removeClass" : "addClass"]("item-hover"),
            i.addClass("ctn-hide").eq(n)[a ? "addClass" : "removeClass"]("ctn-hide")
        })
      }
    };
    a._events(),
      n.exports = a
  }),
  define("module/cart/list/ctn/shipment", ["require", "exports", "module", "jquery", "lib/ui/box/1.0.1/box", "module/cart/common", "lib/gallery/channel/1.0.0/channel", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = (r("#jCart"),
      t("lib/ui/box/1.0.1/box"))
      , a = t("module/cart/common")
      , o = t("lib/gallery/channel/1.0.0/channel")
      , l = o.get("cartPage")
      , s = t("module/common/url-prefix")
      , c = {
      _logisticsBillingPop: function(t) {
        var e = this
          , n = template.render("cart/list/shipment/detail", {});
        e._pop = i.bubble(n, {
          title: "运费明细",
          id: "jZtBox",
          showWithAni: "bounceIn:fast",
          duration: 0,
          width: "350"
        }, t)
      },
      _setShippingMethod: function(t) {
        a.ajax(s["api.cart"] + "/cart/selectDelivery", {
          shopId: t.attr("data-shopid"),
          deliveryId: t.attr("data-value")
        }, function(t) {
          l.fire("refreshList", t)
        })
      },
      _events: function() {}
    };
    c._events(),
      n.exports = c
  }),
  define("module/common/tips", ["require", "exports", "module", "lib/ui/box/1.0.1/box"], function(t, e, n) {
    "use strict";
    var r = t("lib/ui/box/1.0.1/box")
      , i = function(t, e) {
        var n = this;
        this.opt = {
          pop: {},
          ctn: ".jCtnText",
          "max-width": "auto",
          html: "",
          delay: 300,
          alwaysShow: !1
        },
          this.selector = t,
          $.extend(n.opt, e),
          this.init()
      }
      ;
    i.prototype = {
      init: function() {
        var t = this;
        t.opt.alwaysShow ? $(t.selector).each(function() {
          var e = $(this).find(t.opt.ctn).text();
          t._create(e, this)
        }) : this._events()
      },
      _timer: !1,
      hide: function() {
        this._$pop && this._$pop.hide()
      },
      _create: function(t, e) {
        var n, i;
        if (t && 0 != $.trim(t).length) {
          if (n = this,
              i = {
                showWithAni: "none",
                hideWithAni: "none",
                duration: 0
              },
              $.extend(i, n.opt.pop),
              n._$pop = r.bubble('<div class="pop-mouseenter _' + n.selector.replace(".", "") + '" style="max-width:' + n.opt["max-width"] + '" >' + t + "</div>", i, e),
            n.opt.alwaysShow || "0" == n.opt.delay)
            return;
          $(n._$pop.node).on("mouseenter", function() {
            n._timer && clearTimeout(n._timer)
          }).on("mouseleave", function() {
            n._$pop && $(n._$pop.node).off("mouseenter").off("mouseleave"),
              n._hide()
          })
        }
      },
      _hide: function() {
        var t = this;
        t._timer = setTimeout(function() {
          t._$pop && t._$pop.hide(),
          t._timer && clearTimeout(t._timer)
        }, t.opt.delay)
      },
      _events: function() {
        var t = this
          , e = $("body");
        e.on("mouseenter", t.selector, function(e) {
          var n, r = this;
          t._timer && clearTimeout(t._timer),
            n = 0 == t.opt.html.length ? $(r).find(t.opt.ctn).text() : t.opt.html,
          n && 0 != $.trim(n).length && (t._timerForEnter = setTimeout(function() {
            t._create(n, r)
          }, 100))
        }).on("mouseleave", t.selector, function(e) {
          t._timerForEnter && clearTimeout(t._timerForEnter),
            t._hide()
        })
      }
    },
      n.exports = i
  }),
  define("module/cart/list/ctn/tariff", ["require", "exports", "module", "jquery", "lib/ui/box/1.0.1/box", "module/cart/common", "module/common/tips", "lib/gallery/channel/1.0.0/channel"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = (r("#jCart"),
      t("lib/ui/box/1.0.1/box"),
      t("module/cart/common"),
      t("module/common/tips"))
      , a = t("lib/gallery/channel/1.0.0/channel")
      , o = a.get("cartPage")
      , l = null
      , s = {
      _events: function() {
        new i(".jMousePop",{
          delay: 0,
          "max-width": "370px",
          pop: {
            id: "_jTariffPop",
            align: "bl!"
          }
        })
      }
    };
    s._events(),
      o.listen("loaded", function(t) {
        r("._jShowMousePop").closest(".ui-layer").remove(),
          l = new i(".jShowMousePop",{
            alwaysShow: !0,
            pop: {
              align: "br!"
            }
          })
      }),
      n.exports = s
  }),
  define("module/cart/list/ctn/ctn", ["require", "exports", "module", "jquery", "module/cart/common", "module/cart/list/chk", "plug/jquery.goodsCol", "lib/ui/box/1.0.1/box", "template", "lib/gallery/utils/1.0.0/image", "lib/gallery/widget/lazyload/1.0.0/lazyload", "lib/gallery/channel/1.0.0/channel", "lib/core/1.0.0/event/emitter", "module/common/url-prefix", "./module", "./qty-box", "./preferential", "./shipment", "./tariff"], function(t, e, n) {
    "use strict";
    var r, i = t("jquery"), a = t("module/cart/common"), o = t("module/cart/list/chk"), l = (t("plug/jquery.goodsCol"),
      t("lib/ui/box/1.0.1/box")), s = t("template"), c = t("lib/gallery/utils/1.0.0/image"), u = t("lib/gallery/widget/lazyload/1.0.0/lazyload"), d = t("lib/gallery/channel/1.0.0/channel"), h = d.get("cartPage"), f = t("lib/core/1.0.0/event/emitter"), p = t("module/common/url-prefix"), m = i("#jCart"), g = t("./module"), b = (t("./qty-box"),
      t("./preferential"));
    t("./shipment"),
      t("./tariff");
    s.helper("getImgByType", function(t, e) {
      return c.getImageURL(t, e)
    }),
      s.helper("delHtmlTag", function(t) {
        return t ? t.replace(/<[^>]+>/g, "") : ""
      }),
      r = {
        init: function() {
          var t = this;
          t.loadCtn()
        },
        _getListData: function(t) {
          if (!t || 0 == t.length)
            return [];
          return i.each(t, function(t, e) {
            var n = e.totalDiscount;
            e._unusedPreferentialTabHtml = b.getTabHtml(e.unusedPromotions, !1, n),
              e._unusedPreferentialCtnHtml = b.getCtnHtml(e.unusedPromotions, !1, n),
              e._usedPreferentialTabHtml = b.getTabHtml(e.promotions, !0, n),
              e._usedPreferentialCtnHtml = b.getCtnHtml(e.promotions, !0, n),
              i.extend(e, g._getHdData(e)),
            e.cartPkgs && 0 != e.cartPkgs.length && i.each(e.cartPkgs, function(t, e) {
              var n = e.overflowLimitAmount;
              e.unableItems = g._getOtherListData(e.unableItems),
                e.gifts = g._getOtherListData(e.gifts),
                e.unableGifts = g._getOtherListData(e.unableGifts),
                e.items = g._getProductData(e.items),
                e._tariffPop = n ? "" : "jShowMousePop",
                e._totalPop = n ? "jShowMousePop" : ""
            })
          }),
            t
        },
        refreshCtn: function(t) {
          var e, n = this;
          n._getListData(t.groups),
            e = s("cart/list", t),
            m.html(e),
            o.setChk(),
          1 == t.queryFavorite && n._setFavorite(),
            u.load(".jImg"),
            h.fire("loaded", t),
            h.fire("statistics", t)
        },
        loadCtn: function() {
          var t = this;
          a.ajax(p["api.cart"] + "/cart/get", {}, function(e) {
            t.refreshCtn(e)
          }, function() {
            m.html('<div class="error-tips">获取购物车数据失败，请<a href="javascript:;" onclick="window.location.reload()">重试</a></div>')
          })
        },
        _col: function(t) {
          var e = (t.parent(".collection"),
            t.getProducts({
              isChecked: !1
            }));
          t.attr("data-product-id", e.productId),
            t.goodsCol()
        },
        del: function(t) {
          var e = t.getProducts({
            isChecked: !1
          });
          a.ajax(p["api.cart"] + "/cart/del", {
            productId: e.productId
          }, function(t) {
            h.fire("refreshList", t)
          })
        },
        _getProductsId: function() {
          return i(".jProList").map(function() {
            return i(this).attr("data-id")
          }).get().join(",")
        },
        _setFavorite: function() {
          var t = this
            , e = "";
          a.ajax(p["api.cart"] + "/cart/favorite", {
            productIds: t._getProductsId()
          }, function(t) {
            a.isEmptyObject(t.data) || i.each(t.data, function(t, n) {
              var n = Boolean(n);
              e = '<a class="jCol ' + (n ? "btn-disabled" : "") + '" href="javascript:;">' + (n ? "已收藏" : "收藏") + "</a>",
                i(".jProListId-" + t).html(e)
            })
          })
        },
        _events: function() {
          var t = this;
          m.on("click", ".jChkCat", function() {
            var t = i(this)
              , e = t.parent("h1").parent(".list-border")
              , n = e.find(".jChkItem")
              , r = e.find(".jWarehouseChk")
              , a = t.prop("checked");
            t.hasClass("btn-disabled") || (n.each(function() {
              var t = i(this);
              t.prop("disabled") || t.prop("checked", a)
            }),
              r.each(function() {
                var t = i(this);
                t.prop("disabled") || t.prop("checked", a)
              }),
              o.setChk(),
              o.check(m.find(".jChkItem")))
          }),
            m.on("click", ".jChkItem", function() {
              var t = i(this);
              t.hasClass("btn-disabled") || (o.setChk(),
                o.check(m.find(".jChkItem")))
            }),
            m.on("click", ".jWarehouseChk", function() {
              var t, e, n, r = i(this);
              r.hasClass("btn-disabled") || (t = r.closest(".jPkg"),
                e = t.find(".jChkItem"),
                n = r.prop("checked"),
                e.each(function() {
                  var t = i(this);
                  t.prop("disabled") || t.prop("checked", n)
                }),
                o.setChk(),
                o.warehouseChecked(m.find(".jChkItem")))
            }),
            m.on("click", ".jCol", function() {
              var e = i(this);
              e.hasClass("btn-disabled") || t._col(e)
            }),
            m.on("click", ".jDel", function() {
              var e = i(this);
              e.hasClass("btn-disabled") || l.confirm("确定删除吗,删除后不可以恢复哦？", function(n) {
                n && t.del(e)
              }, {
                sender: e[0],
                id: "confirm"
              })
            })
        }
      },
      r._events(),
      f.applyTo(r),
      n.exports = r
  }),
  define("module/cart/list/bar", ["require", "exports", "module", "jquery", "lib/core/1.0.0/io/cookie", "lib/gallery/ssologin/1.0.0/ssologin", "lib/gallery/channel/1.0.0/channel", "lib/ui/box/1.0.1/box", "lib/core/1.0.0/event/emitter", "module/cart/common", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = t("lib/core/1.0.0/io/cookie")
      , a = t("lib/gallery/ssologin/1.0.0/ssologin")
      , o = t("lib/gallery/channel/1.0.0/channel")
      , l = o.get("cartPage")
      , s = t("lib/ui/box/1.0.1/box")
      , c = t("lib/core/1.0.0/event/emitter")
      , u = t("module/cart/common")
      , d = r("#jCart")
      , h = t("module/common/url-prefix")
      , f = {
      _isFirstLoad: !0,
      init: function() {
        var t = this;
        t.checkSubmit(),
          t.setFloatLayer(),
          t.setFailBtnStatus(),
        t._isFirstLoad && t._events(),
          t._isFirstLoad = !1
      },
      tipsForLimit: function() {
        var t = this
          , e = r("[data-tariff-limit]")
          , n = e.eq(0);
        n.find(".jTariffPopBox").text();
        n.addClass("pkg-selected"),
          t.popStyle(n)
      },
      popStyle: function(t) {
        r("body,html").animate({
          scrollTop: t.position().top + t.height() - 30
        }, 400)
      },
      tipsForTariff: function() {
        var t = this
          , e = r("[data-tariff-up]")
          , n = e.eq(0);
        n.find(".jTariffPopBoxUp").text();
        n.addClass("pkg-selected"),
          u.o.attr({
            "data-tariff-flag": 1,
            "data-tariff-txt": "继续结算"
          }),
          r("#jSubmit").text("继续结算"),
          t.popStyle(n)
      },
      checkSubmit: function() {
        var t, e, n = r("#jSubmit");
        return u.o.attr("data-tariff-flag") && n.text(u.o.attr("data-tariff-txt")),
          n ? (t = d.find(".jChkItem"),
            e = 0,
            t.each(function() {
              var t = r(this);
              t.prop("checked") && e++
            }),
            e > 0 ? (n.removeClass("btn-disabled"),
              !1) : (n.addClass("btn-disabled"),
              !0)) : !1
      },
      validateLimit: function(t) {
        var e = r(t)
          , n = e.find(".jChkItem:not(:disabled):checked");
        return 0 == e.length || 0 == n.length
      },
      setFloatLayer: function() {
        var t, e, n, i, a = r("#jSettlement");
        0 != a.length && (t = r("#jSetBox"),
          e = a.offset().top + t.outerHeight() + 20,
          n = r(window).scrollTop() + r(window).height(),
          i = e >= n,
          t[(i ? "add" : "remove") + "Class"]("settlement-float"))
      },
      batchDel: function(t) {
        var e = t.getProducts();
        e.productId.length > 0 ? u.ajax(h["api.cart"] + "/cart/del", {
          productId: e.productId
        }, function(t) {
          l.fire("refreshList", t)
        }) : s.warn("请至少选择一项！", t)
      },
      setFailBtnStatus: function() {
        var t = 0 == r(".jFailPro").length;
        r(".jEmptyFail")[t ? "hide" : "show"]()
      },
      batchEmp: function() {
        u.ajax(h["api.cart"] + "/cart/clear", {}, function(t) {
          l.fire("refreshList", t)
        })
      },
      emptyFail: function() {
        var t = r(".jFailPro").map(function() {
          return r(this).attr("data-id")
        }).get().join(",");
        u.ajax(h["api.cart"] + "/cart/del", {
          productId: t
        }, function(t) {
          l.fire("refreshList", t)
        })
      },
      gotoOrder: function() {
        var t = r("#jSubmit")
          , e = this;
        if (i("_nick")) {
          if (e.checkSubmit())
            return s.warn("请至少选择一个商品!"),
              !1;
          window.location.href = t.attr("href")
        } else
          a.showDialog(function(n) {
            e.gotoOrder(t)
          }, function(t) {})
      },
      _events: function() {
        var t = this;
        r(window).scroll(function() {
          t.setFloatLayer()
        }),
          r("body,html").resize(function() {
            t.setFloatLayer()
          }),
          d.on("click", ".jDelByBatch", function() {
            var e = r(this)
              , n = 0 != r(".jChkItem:checked").length;
            return n ? void (e.hasClass("btn-disabled") || s.confirm("确定删除吗,删除后不可以恢复哦？", function(e) {
              e && t.batchDel(d.find(".jChkItem"))
            })) : void s.warn("请至少选择一项！", this)
          }),
          d.on("click", ".jEmpByBatch", function() {
            var e = r(this)
              , n = 0 != r(".jAllTableItem").length;
            return n ? void s.confirm("确定清除购物车吗,清除后不可以恢复哦？", function(e) {
              e && t.batchEmp()
            }) : void s.warn("没有可清除的商品", null , e)
          }),
          d.on("click", ".jEmptyFail", function() {
            r(this);
            t.emptyFail()
          }),
          d.on("click", "#jSubmit", function() {
            if (r(this).hasClass("btn-disabled"))
              return !1;
            var e = t.validateLimit("[data-tariff-limit]");
            return r(this)[e ? "removeClass" : "addClass"]("btn-disabled"),
              e ? t.validateLimit("[data-tariff-up]") || u.o.attr("data-tariff-flag") ? (t.gotoOrder(),
                !1) : (t.tipsForTariff(),
                !1) : (t.tipsForLimit(),
                !1)
          })
      }
    };
    l.listen("loaded", function(t) {
      f.init(t)
    }),
      c.applyTo(f),
      n.exports = f
  }),
  define("module/cart/list/list", ["require", "exports", "module", "jquery", "lib/gallery/channel/1.0.0/channel", "./ctn/ctn", "./bar"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = t("lib/gallery/channel/1.0.0/channel")
      , a = i.get("cartPage")
      , o = (r("#jCart"),
      t("./ctn/ctn"))
      , l = t("./bar")
      , s = {
      init: function() {
        o.init()
      },
      refreshList: function(t) {
        o.refreshCtn(t),
          l.init(t)
      }
    };
    a.listen("refreshList", function(t) {
      s.refreshList(t)
    }),
      n.exports = s
  }),
  define("module/cart/you-like", ["require", "exports", "module", "jquery", "module/cart/common", "pub/module/ajax/1.0.0/ajax", "lib/gallery/widget/lazyload/1.0.0/lazyload", "lib/ui/box/1.0.1/box", "lib/gallery/channel/1.0.0/channel", "module/common/url-prefix"], function(t, e, n) {
    "use strict";
    var r = t("jquery")
      , i = t("module/cart/common")
      , a = t("pub/module/ajax/1.0.0/ajax")
      , o = t("lib/gallery/widget/lazyload/1.0.0/lazyload")
      , l = t("lib/ui/box/1.0.1/box")
      , s = t("lib/gallery/channel/1.0.0/channel")
      , c = s.get("cartPage")
      , u = r("#jShopCart")
      , d = t("module/common/url-prefix")
      , h = {
      isFirstLoad: !0,
      math: 1,
      data: {},
      current: function(t, e) {
        var n = this
          , i = n.data
          , a = "";
        for (t = t; e > t; t++)
          i[t] && (a += '<li class="g-item"><a target="_blank" data-bpm="11.1.1.' + (t + 1) + ".0." + i[t].productId + '" href="' + i[t].url + '"><img class="jImg jYouLikeImg img-error" data-url="' + i[t].img + '" src=""></a><span class="g-intro"><a data-bpm="11.1.1.' + (t + 1) + ".0." + i[t].productId + '" target="_blank" href="' + i[t].url + '">' + i[t].name + '</a></span><span class="g-price">￥' + i[t].price + '</span><a href="javascript:;" class="btn jAddCart"  data-product-id="' + i[t].productId + '" data-bpm="11.1.1.' + (t + 1) + ".0." + i[t].productId + '">加入购物车</a></li>');
        a += "</ul>",
          r("#jShopCart .jDomHtmlLi").html(a),
          o.load("#jShopCart .jYouLikeImg"),
          a = "",
          n.addcart()
      },
      init: function() {
        var t = this;
        t.loaded(),
        t.isFirstLoad && t._events(),
          t.isFirstLoad = !1
      },
      loaded: function() {
        var t, e, n = this, i = [], o = r(".jProInfo");
        for (t = 0; t < o.length; t++)
          i.push(o.eq(t).attr("data-id"));
        0 != i.length && (e = {
          pids: i.join(",")
        },
          a.jsonp({
            url: d["api.mall"] + "/bi/getRel",
            data: e
          }, function(t) {
            return t._error ? void u.hide() : (n.data = t,
              r("#jShopCart").show(),
              n.math = Math.ceil(t.length / 10),
              n.current(0, 10),
              void (1 == n.math && (r(".jNavLeft").hide(),
                r(".jNavRight").hide())))
          }, function() {
            u.hide()
          }))
      },
      addcart: function() {
        r(".jAddCart").click(function() {
          var t = this
            , e = r(this);
          i.ajax(d["api.cart"] + "/cart/add", {
            productId: e.attr("data-product-id"),
            quantity: 1
          }, function(e) {
            l.ok("成功加入购物车", t),
              c.fire("init")
          }, function(t) {}, t)
        })
      },
      _events: function() {
        var t = this;
        u.on("mouseenter", ".jDomHtmlLi li", function() {
          var t = r(".jDomHtmlLi li").index(this);
          r(".jAddCart").eq(t).show()
        }).on("mouseleave", ".jDomHtmlLi li", function() {
          r(".jAddCart").hide()
        }).on("click", ".jNavRight", function() {
          var e = u.attr("data-page")
            , n = parseInt(e);
          return e == t.math ? (t.current(0, 10),
            u.attr("data-page", "1"),
            !1) : (t.current(10 * n, 10 * n + 10),
            void u.attr("data-page", n + 1))
        }).on("click", ".jNavLeft", function() {
          var e = u.attr("data-page")
            , n = parseInt(e);
          return 1 == e ? (t.current(10 * t.math - 10, 10 * t.math),
            u.attr("data-page", t.math),
            !1) : (t.current(10 * (n - 1) - 10, 10 * n - 10),
            void u.attr("data-page", n - 1))
        })
      }
    };
    c.listen("loaded", function(t) {
      h.init(t)
    }),
      n.exports = h
  }),
  define("conf/cart", ["require", "exports", "module", "jquery", "lib/gallery/channel/1.0.0/channel", "module/cart/list/list", "module/cart/you-like"], function(t, e, n) {
    "use strict";
    var r, i, a, o = t("jquery"), l = t("lib/gallery/channel/1.0.0/channel");
    l.define("cartPage", ["statistics", "init", "loaded", "refreshList"]),
      r = t("module/cart/list/list"),
      i = t("module/cart/you-like"),
      a = {
        init: function() {
          o("#jHdTxt").html("购物车"),
            r.init()
        }
      },
      a.init()
  });
