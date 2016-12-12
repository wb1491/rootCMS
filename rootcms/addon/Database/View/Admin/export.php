<?php
 if (!defined('SHUIPF_VERSION')) exit();
/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++)
        $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
 ?>
<admintemplate file="Common/Head"/>
<body>
<div class="wrap J_check_wrap">
  <admintemplate file="Common/Nav"/>
  <form class="J_ajaxForm" id="export-form" action="{:url('Database/export',array('isadmin'=>1 ))}" method="post">
  <div class="table_list">
    <table width="100%">
      <thead>
        <tr>
          <td width="48"><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x" ></td>
          <td>表名</td>
          <td width="120">数据量</td>
          <td width="120">数据大小</td>
          <td width="160">创建时间</td>
          <td width="160">备份状态</td>
          <td width="120">操作</td>
        </tr>
      </thead>
      <volist name="list" id="vo">
        <tr>
          <td class="num"><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="tables[]" value="{$vo.name}"></td>
          <td>{$vo.name}</td>
          <td>{$vo.rows}</td>
          <td>{$vo.data_length|format_bytes}</td>
          <td>{$vo.create_time}</td>
          <td class="info">未备份</td>
          <td class="action"><a href="{:url('Database/optimization',array('tables'=>$vo['name'],'isadmin'=>1 ))}">优化表</a>&nbsp; <a href="{:url('Database/repair',array('tables'=>$vo['name'],'isadmin'=>1 ))}">修复表</a></td>
        </tr>
      </volist>
    </table>
  </div>
  <div class="btn_wrap">
      <div class="btn_wrap_pd">
        <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>                
        <button class="btn" type="submit" id="export">立即备份</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="{:url('Database/optimization',array('isadmin'=>1 ))}">优化表</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="{:url('Database/repair',array('isadmin'=>1 ))}">修复表</button>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js"></script>
<script>
$(function () {
    var $export = $("#export");
    var $form = $("#export-form");
    $export.click(function () {
        $export.parent().children().addClass("disabled");
        $export.html("正在发送备份请求...");
        $.post(
            $form.attr("action"),
            $form.serialize(),
            function (data) {
                if (data.status) {
                    tables = data.tables;
                    $export.html(data.info + "开始备份，请不要关闭本页面！");
                    backup(data.tab);
                    window.onbeforeunload = function () {
                        return "正在备份数据库，请不要关闭！"
                    }
                } else {
                    alert(data.info);
                    $export.parent().children().removeClass("disabled");
                    $export.html("立即备份");
                }
            },
            "json"
        );
        return false;
    });

    function backup(tab, status) {
        status && showmsg(tab.id, "开始备份...(0%)");
        $.get($form.attr("action"), tab, function (data) {
            if (data.status) {
                showmsg(tab.id, data.info);

                if (!$.isPlainObject(data.tab)) {
                    $export.parent().children().removeClass("disabled");
                    $export.html("备份完成，点击重新备份");
                    window.onbeforeunload = function () {
                        return null
                    }
                    return;
                }
                backup(data.tab, tab.id != data.tab.id);
            } else {
                alert(data.info);
                $export.parent().children().removeClass("disabled");
                $export.html("立即备份");
            }
        }, "json");

    }

    function showmsg(id, msg) {
        $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg)
    }

});
</script>
</body>
</html>