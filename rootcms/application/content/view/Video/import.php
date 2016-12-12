<?php if (!defined('SHUIPF_VERSION')) exit(); ?>
<Admintemplate file="Common/Head"/>
<body class="body_none" style="width:600px;">
<div class="wrap_pop">
  <form class="J_ajaxForm" action="{:url('import')}" method="post">
    <div class="pop_cont pop_table" style="overflow-x:hidden;">
      <input type="hidden" name="videourl" value="{$videofile}"/>
      <input type="hidden" name="path" value="{$path}"/>
      <input type="hidden" name="file" value="{$file}"/>
      <input type="hidden" name="status" value="99"/>
      <table width="100%">
        <colgroup>
        <col/>
        </colgroup>
        <tr>
          <th width="80">导入栏目：</th>
          <td class="y-bg"><?php
                echo Form::select($category,0,"name='catid'");
                ?></td>
        </tr>
        <tr>
          <th width="80">标题：</th>
          <td class="y-bg">
            <input type="text" name='title' value="{$data.title}" class="input length_2" style='width:90%'/>
          </td>
        </tr>
        <tr>
          <th width="80">描述：</th>
          <td class="y-bg">
            <textarea name='description' style='width:90%;height:60px;'>{$data.description}</textarea>
          </td>
        </tr>
        <tr>
          <th width="80">缩略图：</th>
          <td class="y-bg">
            <input type="text" name='thumb' value="{$data.thumb}" class="input length_2" style='width:90%'/>
          </td>
        </tr>
        <tr>
          <th width="80">内容：</th>
          <td class="y-bg">
            <textarea name='content' style='width:90%;height:150px;'>{$data.content}</textarea>
          </td>
        </tr>
      </table>
    </div>
    <div class="pop_bottom">
      <button class="btn fr" id="J_dialog_close" type="button">取消</button>
      <button type="submit" class="btn btn_submit J_ajax_submit_btn fr mr10">提交</button>
    </div>
  </form>
</div>
<script src="{$config_siteurl}statics/js/common.js"></script>
</body>
</html>