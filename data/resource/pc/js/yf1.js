var PRICE_FORMAT = '&yen;%s';
    $(function () {
        //头部搜索S
        // input ajax tips
        $('#keyword').focus(function () {
            if ($(this).val() == $(this).attr('title')) {
                $(this).val('').removeClass('tips');
            }
        }).blur(function () {
            if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
                $(this).addClass('tips').val($(this).attr('title'));
            }
        }).blur().autocomplete({
            source: function (request, response) {
                $.getJSON('http://mall.htths.com/yf_shop/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                    $('#top_search_box > ul').unwrap();
                    response(data);
                    if (status == 'success') {
                        $('body > ul:last').wrap("<div id='top_search_box'></div>").css({
                            'zIndex': '1000',
                            'width': '362px'
                        });
                    }
                });
            },
            select: function (ev, ui) {
                $('#keyword').val(ui.item.label);
                $('#top_search_form').submit();
            }
        });

        $('#button').click(function () {
            if ($('#keyword').val() == '') {
                if ($('#keyword').attr('data-value') == '') {
                    return false
                } else {
                    window.location.href = "http://mall.htths.com/yf_shop/index.php?act=search&op=index&keyword=" + $('#keyword').attr('data-value');
                    return false;
                }
            }
        });
        $(".head-search-bar").hover(null,
          function () {
              $('#search-tip').hide();
          });
        // input ajax tips
        $('#keyword').focus(function () {
            $('#search-tip').show()
        }).autocomplete({
            //minLength:0,
            source: function (request, response) {
                $.getJSON('http://mall.htths.com/yf_shop/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                    $('#top_search_box > ul').unwrap();
                    response(data);
                    if (status == 'success') {
                        $('#search-tip').hide();
                        $(".head-search-bar").unbind('mouseover');
                        $('body > ul:last').wrap("<div id='top_search_box'></div>").css({
                            'zIndex': '1000',
                            'width': '362px'
                        });
                    }
                });
            },
            select: function (ev, ui) {
                $('#keyword').val(ui.item.label);
                $('#top_search_form').submit();
            }
        });
        $('#search-his-del').on('click', function () {
            $.cookie('ht_his_sh', null, {path: '/'});
            $('#search-his-list').empty();
        });
        //头部搜索E
        //动画显示边条内容区域S
        appbar();
        $(window).resize(function () {
            appbar();
        });
        function appbar() {
            if ($(window).width() >= 1240) {
                $('#appbar_tabs >.appbar-tab-bottom').show();
            } else {
                $('#appbar_tabs >.appbar-tab-bottom').hide();
            }
        }
        $('#appbar_tabs').hover(
          function () {
              $('#appbar_tabs >.appbar-tab-bottom').show();
          },
          function () {
              appbar();
          }
        );
        $("#appbar_cart").click(function () {
            if ($("#content-cart").css('right') == '-210px') {
                $("#content-cart").animate({right: '35px'});
                if (!$("#appbar_cartlist").html()) {
                    $("#appbar_cartlist").load('index.php?controller=cart&action=ajax_load&type=html');
                }
            } else {
                $(".close").click();
                $(".chat-list").css("display", 'none');
            }
        });
        $(".close").click(function () {
            $(".appbar-content-box").animate({right: '-210px'});
        });
//动画显示边条内容区域E
//返回顶部 S
        backTop = function (btnId) {
            var btn = document.getElementById(btnId);
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            window.onscroll = set;
            btn.onclick = function () {
                btn.style.opacity = "0.5";
                window.onscroll = null;
                this.timer = setInterval(function () {
                    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                    scrollTop -= Math.ceil(scrollTop * 0.1);
                    if (scrollTop == 0) clearInterval(btn.timer, window.onscroll = set);
                    if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop = scrollTop;
                    if (document.body.scrollTop > 0) document.body.scrollTop = scrollTop;
                }, 10);
            };
            function set() {
                scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                btn.style.opacity = scrollTop ? '1' : "0.5";
            }
        };
        backTop('gotop');
//返回顶部 E



    });




