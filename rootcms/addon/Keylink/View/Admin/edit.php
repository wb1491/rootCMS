<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap">
  <Admintemplate file="Common/Nav"/>
  <div class="h_a">编辑关键词链接</div>
  <form name="myform" action="{:url('edit','isadmin=1')}" method="post" class="J_ajaxForm">
    <div class="table_full">
      <table width="100%" class="table_form contentWrap">
        <tr>
          <th  width="80">关键词</th>
          <td><input type="text" name="word" value="{$info.word}" class="input length_2"/></td>
        </tr>
        <tr>
          <th>链接</th>
          <td><input type="text" name="url" class="input length_6" size="5" value="{$info.url}"/></td>
        </tr>
        <tr>
          <th>替换次数</th>
          <td><input type="text" name="frequency" class="input" size="5" value="{$info.frequency}"/> 次</td>
        </tr>
      </table>
    </div>
    <div class="">
      <div class="btn_wrap_pd">
        <input type="hidden" name="keylinkid" value="{$info.keylinkid}">
        <button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
      </div>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js?v"></script>
</body>
</html>