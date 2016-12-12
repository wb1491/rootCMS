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
    <div class="table_list">
      <table width="100%">
        <thead>
          <tr>
            <td width="200">备份名称</td>
            <td width="80">卷数</td>
            <td width="80">压缩</td>
            <td width="80">数据大小</td>
            <td width="200">备份时间</td>
            <td>状态</td>
            <td width="150">操作</td>
          </tr>
        </thead>
        <volist name="list" id="data">
          <tr>
            <td>{$data.time|date='Ymd-His',###}</td>
            <td>{$data.part}</td>
            <td>{$data.compress}</td>
            <td>{$data.size|format_bytes}</td>
            <td>{$key}</td>
            <td>-</td>
            <td class="action"><a class="db-import" href="{:url('Database/import',array('time'=>$data['time'],'isadmin'=>1 ))}">还原</a>&nbsp; <a href="{:url('Database/del',array('time'=>$data['time'],'isadmin'=>1 ))}" class="J_ajax_del">删除</a>&nbsp; <a href="{:url('Database/download',array('time'=>$data['time'],'isadmin'=>1 ))}">下载</a></td>
          </tr>
        </volist>
      </table>
    </div>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
<script type="text/javascript">
$(".db-import").click(function () {
    var self = this,
        status = ".";
    $.get(self.href, success, "json");
    window.onbeforeunload = function () {
        return "正在还原数据库，请不要关闭！"
    }
    return false;

    function success(data) {
        if (data.status) {
            if (data.gz) {
                data.info += status;
                if (status.length === 5) {
                    status = ".";
                } else {
                    status += ".";
                }
            }
            $(self).parent().prev().text(data.info);
            if (data.part) {
                $.get(self.href, {
                        "part": data.part,
                        "start": data.start
                    },
                    success,
                    "json"
                );
            } else {
                window.onbeforeunload = function () {
                    return null;
                }
            }
        } else {
            alert(data.info);
        }
    }
});
</script>
</body>
</html>