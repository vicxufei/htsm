/**
 * Created by yefeng on 16/4/28.
 */


/*
 * 地址联动选择
 * input不为空时出现编辑按钮，点击按钮进行联动选择
 *
 * 使用范例：
 * [html]
 * <input id="region" name="region" type="hidden" value="" >
 * [javascrpt]
 * $("#region").nc_region();
 *
 * 默认从cache读取地区数据，如果需从数据库读取（如后台地区管理），则需设置定src参数
 * $("#region").nc_region({{src:'db'}});
 *
 * 如需指定地区下拉显示层级，需指定show_deep参数，默认未限制
 * $("#region").nc_region({{show_deep:2}}); 这样最多只会显示2级联动
 *
 * 系统分自动将已经选择的地区ID存放到ID依次为_area_1、_area_2、_area_3、_area_4、_area的input框中
 * _area存放选中的最后一级ID
 * 这些input框需要手动在模板中创建
 *
 * 取得已选值mpf="sqde"
 * $('#region').val() ==> 河北 石家庄市 井陉矿区
 * $('#region').fetch('islast')  ==> true; 如果非最后一级，返回false
 * $('#region').fetch('area_id') ==> 1127
 * $('#region').fetch('area_ids') ==> 3 73 1127
 * $('#region').fetch('selected_deep') ==> 3 已选择分类的深度
 * $("#region").fetch('area_id_1') ==> 3
 * $("#region").fetch('area_id_2') ==> 73
 * $("#region").fetch('area_id_3') ==> 1127
 * $("#region").fetch('area_id_4') ==> ''
 */

(function($) {
  $.fn.nc_region = function(options) {
    var shopUrl= 'http://www.htths.com';
    var $region = $(this);
    var settings = $.extend({}, {
      area_id: 0,
      region_span_class: "_region_value",
      src: "cache",
      show_deep: 0,
      btn_style_html: "",
      tip_type: ""
    }, options);
    settings.islast = false;
    settings.selected_deep = 0;
    settings.last_text = "";
    this.each(function() {
      var $inputArea = $(this);
      console.log($inputArea.val());
      if ($inputArea.val() == "") {
        initArea($inputArea)
      } else {
        var $region_span = $('<span id="_area_span" class="' + settings.region_span_class + '">' + $inputArea.val() + "</span>");
        var $region_btn = $('<input type="button" class="input-btn" ' + settings.btn_style_html + ' value="编辑" />');
        $inputArea.after($region_span);
        $region_span.after($region_btn);
        $region_btn.on("click", function() {
          $region_span.remove();
          $region_btn.remove();
          initArea($inputArea)
        });
        settings.islast = true
      }
      this.settings = settings;
      if ($inputArea.val() && /^\d+$/.test($inputArea.val())) {
        $.getJSON(shopUrl + "/index.php?controller=index&action=json_area_show&area_id=" + $inputArea.val() + "&callback=?", function(data) {
          $("#_area_span").html(data.text == null ? "无" : data.text)
        })
      }
    });

    function initArea($inputArea) {
      settings.$area = $("<select></select>");
      $inputArea.before(settings.$area);
      loadAreaArray(function() {
        loadArea(settings.$area, settings.area_id)
      })
    }
    function loadArea($area, area_id) {
      if ($area && nc_a[area_id].length > 0) {
        var areas = [];
        areas = nc_a[area_id];
        if (settings.tip_type && settings.last_text != "") {
          $area.append("<option value=''>" + settings.last_text + "(*)</option>")
        } else {
          $area.append("<option value=''>-请选择-</option>")
        }
        for (i = 0; i < areas.length; i++) {
          $area.append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>")
        }
        settings.islast = false
      }
      $area.on("change", function() {
        var region_value = "",
          area_ids = [],
          selected_deep = 1;
        $(this).nextAll("select").remove();
        $region.parent().find("select").each(function() {
          if ($(this).find("option:selected").val() != "") {
            region_value += $(this).find("option:selected").text() + " ";
            area_ids.push($(this).find("option:selected").val())
          }
        });
        settings.selected_deep = area_ids.length;
        settings.area_ids = area_ids.join(" ");
        $region.val(region_value);
        settings.area_id_1 = area_ids[0] ? area_ids[0] : "";
        settings.area_id_2 = area_ids[1] ? area_ids[1] : "";
        settings.area_id_3 = area_ids[2] ? area_ids[2] : "";
        settings.area_id_4 = area_ids[3] ? area_ids[3] : "";
        settings.last_text = $region.prevAll("select").find("option:selected").last().text();
        var area_id = settings.area_id = $(this).val();
        if ($('#_area_1').length > 0) $("#_area_1").val(settings.area_id_1);
        if ($('#_area_2').length > 0) $("#_area_2").val(settings.area_id_2);
        if ($('#_area_3').length > 0) $("#_area_3").val(settings.area_id_3);
        if ($('#_area_4').length > 0) $("#_area_4").val(settings.area_id_4);
        if ($('#_area').length > 0) $("#_area").val(settings.area_id);
        if ($('#_areas').length > 0) $("#_areas").val(settings.area_ids);
        if (settings.show_deep > 0 && $region.prevAll("select").size() == settings.show_deep) {
          settings.islast = true;
          if (typeof settings.last_click == 'function') {
            settings.last_click(area_id);
          }
          return
        }
        if (area_id > 0) {
          if (nc_a[area_id] && nc_a[area_id].length > 0) {
            var $newArea = $("<select></select>");
            $(this).after($newArea);
            loadArea($newArea, area_id);
            settings.islast = false
          } else {
            settings.islast = true;
            if (typeof settings.last_click == 'function') {
              settings.last_click(area_id);
            }
          }
        } else {
          settings.islast = false
        }
        if ($('#islast').length > 0) $("#islast").val("");
      })
    }
    function loadAreaArray(callback) {
      if (typeof nc_a === "undefined") {
        $.getJSON(shopUrl + "/index.php?controller=index&action=json_area&src=" + settings.src + "&callback=?", function(data) {
          nc_a = data;
          callback()
        })
      } else {
        callback()
      }
    }
    if (typeof jQuery.validator != 'undefined') {
      jQuery.validator.addMethod("checklast", function(value, element) {
        return $(element).fetch('islast');
      }, "请将地区选择完整");
    }
  };
  $.fn.fetch = function(k) {
    var p;
    this.each(function() {
      if (this.settings) {
        p = eval("this.settings." + k);
        return false
      }
    });
    return p
  }
})(jQuery);
